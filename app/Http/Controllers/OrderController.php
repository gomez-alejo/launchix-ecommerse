<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Mostrar listado de pedidos del usuario
     */
    public function index(Request $request)
    {
        $query = Order::with('items.product')
            ->forUser(Auth::id())
            ->latest();

        // Filtro por estado
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $orders = $query->paginate(10);

        return view('modals.login-items.user.OrderDetail', compact('orders'));
    }

    /**
     * Mostrar detalles de un pedido específico
     */
    public function show(Order $order)
    {
        // Verificar que el pedido pertenece al usuario actual
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        $order->load('items.product');

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Crear un nuevo pedido (desde el carrito)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_zip_code' => 'required|string|max:10',
            'payment_method' => 'required|in:cash,card,transfer,paypal',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Calcular totales
            $subtotal = 0;
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Verificar stock disponible
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];

                // Reducir stock y aumentar ventas
                $product->decrement('stock', $item['quantity']);
                $product->increment('sales', $item['quantity']);
            }

            // Calcular costos adicionales (puedes personalizar esto)
            $shippingCost = $subtotal >= 100000 ? 0 : 10000; // Envío gratis si supera $100.000
            $tax = $subtotal * 0.19; // IVA 19%
            $discount = $request->discount ?? 0;
            $total = $subtotal + $shippingCost + $tax - $discount;

            // Crear el pedido
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'status' => 'Pending',
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_address' => $validated['shipping_address'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_state' => $validated['shipping_state'],
                'shipping_zip_code' => $validated['shipping_zip_code'],
                'shipping_country' => 'Colombia',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Crear los items del pedido
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Pedido creado exitosamente. Número de orden: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al crear el pedido: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancelar un pedido
     */
    public function cancel(Request $request, Order $order)
    {
        // Verificar que el pedido pertenece al usuario actual
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar este pedido.');
        }

        // Verificar si se puede cancelar
        if (!$order->canBeCancelled()) {
            return redirect()
                ->back()
                ->with('error', 'Este pedido no puede ser cancelado en su estado actual.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Restaurar stock de los productos
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                    $product->decrement('sales', $item->quantity);
                }
            }

            // Actualizar el pedido
            $order->update([
                'status' => 'Cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $validated['cancellation_reason'],
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pedido cancelado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Descargar factura (PDF) - implementar según necesidad
     */
    public function downloadInvoice(Order $order)
    {
        // Verificar que el pedido pertenece al usuario actual
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para descargar esta factura.');
        }

        // Aquí implementarías la generación del PDF
        // Por ejemplo, usando Laravel Dompdf o similar
        // return PDF::loadView('invoices.order', compact('order'))->download('factura-' . $order->order_number . '.pdf');

        return redirect()->back()->with('info', 'Función de descarga de factura en desarrollo.');
    }
}
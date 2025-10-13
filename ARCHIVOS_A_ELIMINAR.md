# 🗑️ LISTA DE ARCHIVOS A ELIMINAR

## Archivos de Migración a ELIMINAR

### ❌ database/migrations/2025_10_09_000001_improve_servicios_table.php
**Razón:** Contenido será consolidado en `create_servicios_table.php`
**Impacto:** Ninguno - los cambios ya están aplicados en la BD
**Contenido que se consolida:**
- Foreign key user_id → entrepreneurs(id)
- Índices en categoria, precio_base, status
- Soft deletes (deleted_at)
- Campo status enum

### ❌ database/migrations/2025_10_09_000002_improve_products_table.php  
**Razón:** Contenido será consolidado en `create_products_table.php`
**Impacto:** Ninguno - los cambios ya están aplicados en la BD
**Contenido que se consolida:**
- Índices en category, price, stock, status, featured, views
- Soft deletes (deleted_at)
- Campos: status, featured, discount_percentage, views

### ❌ database/migrations/2025_10_09_000003_improve_product_images_table.php
**Razón:** Contenido será consolidado en `create_product_images_table.php`
**Impacto:** Ninguno - los cambios ya están aplicados en la BD
**Contenido que se consolida:**
- Campos: alt_text, is_primary, display_order, file_size, mime_type
- Índices en is_primary, display_order

### ❌ database/migrations/2025_10_09_000005_optimize_users_table.php
**Razón:** Contenido será consolidado en `create_users_table.php`
**Impacto:** Ninguno - los cambios ya están aplicados en la BD
**Contenido que se consolida:**
- Soft deletes (deleted_at)
- Campos: avatar, phone_verified_at
- Índices en city, department, username

## Archivos a MANTENER (Sin cambios)

### ✅ 2025_10_09_000004_create_favorites_table.php
**Razón:** Es una tabla completamente nueva, no hay conflictos
**Estado:** Mantener tal como está

### ✅ Todas las demás migraciones originales
**Razón:** Solo serán modificadas, no eliminadas
**Acción:** Consolidar contenido de las migraciones eliminadas

---

## Comandos para Eliminar Archivos

```bash
# Eliminar archivos de migración redundantes
rm database/migrations/2025_10_09_000001_improve_servicios_table.php
rm database/migrations/2025_10_09_000002_improve_products_table.php  
rm database/migrations/2025_10_09_000003_improve_product_images_table.php
rm database/migrations/2025_10_09_000005_optimize_users_table.php

# Verificar que se eliminaron
ls -la database/migrations/2025_10_09_*
# Solo debe quedar: 2025_10_09_000004_create_favorites_table.php
```

## Scripts SQL para Limpiar Tabla migrations

```sql
-- Eliminar registros de migraciones que ya no existirán como archivos
DELETE FROM migrations WHERE migration = '2025_10_09_000001_improve_servicios_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000002_improve_products_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000003_improve_product_images_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000005_optimize_users_table';

-- Verificar que se eliminaron correctamente
SELECT migration FROM migrations WHERE migration LIKE '%2025_10_09%';
-- Solo debe mostrar: 2025_10_09_000004_create_favorites_table
```

## Verificación Post-Eliminación

```bash
# Verificar que solo existen 23 migraciones (27 - 4 = 23)
ls database/migrations/ | wc -l

# Verificar que no hay referencias rotas
php artisan migrate:status
# No debe mostrar migraciones "not found"
```

---

## ⚠️ IMPORTANTE

**ANTES de eliminar cualquier archivo:**
1. ✅ Hacer backup completo de la carpeta migrations
2. ✅ Hacer backup de la base de datos
3. ✅ Verificar que la funcionalidad web actual funciona
4. ✅ Confirmar que tienes plan de rollback

**Solo eliminar DESPUÉS de:**
1. ✅ Crear las migraciones consolidadas
2. ✅ Probar en base de datos de testing
3. ✅ Verificar que migrate:fresh funciona sin errores

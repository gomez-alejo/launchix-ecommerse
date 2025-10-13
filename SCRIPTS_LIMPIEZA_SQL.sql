-- ============================================================================
-- SCRIPTS SQL PARA LIMPIAR TABLA MIGRATIONS
-- ============================================================================

-- ⚠️ EJECUTAR ANTES DE IMPLEMENTAR LA CONSOLIDACIÓN

-- ----------------------------------------------------------------------------
-- 1. VERIFICAR ESTADO ACTUAL DE LA TABLA MIGRATIONS
-- ----------------------------------------------------------------------------

-- Ver todas las migraciones actuales ordenadas por batch
SELECT id, migration, batch
FROM migrations
ORDER BY batch, id;

-- Contar total de migraciones registradas
SELECT COUNT(*) as total_migraciones FROM migrations;

-- Verificar migraciones específicas que serán eliminadas
SELECT migration, batch
FROM migrations
WHERE migration IN (
    '2025_10_09_000001_improve_servicios_table',
    '2025_10_09_000002_improve_products_table',
    '2025_10_09_000003_improve_product_images_table',
    '2025_10_09_000005_optimize_users_table'
);

-- ----------------------------------------------------------------------------
-- 2. BACKUP DE LA TABLA MIGRATIONS (RECOMENDADO)
-- ----------------------------------------------------------------------------

-- Crear tabla de respaldo
CREATE TABLE migrations_backup_20251009 AS SELECT * FROM migrations;

-- Verificar que el backup se creó correctamente
SELECT COUNT(*) FROM migrations_backup_20251009;

-- ----------------------------------------------------------------------------
-- 3. ELIMINAR REGISTROS DE MIGRACIONES QUE SERÁN CONSOLIDADAS
-- ----------------------------------------------------------------------------

-- Eliminar registros de migraciones de corrección que serán consolidadas
DELETE FROM migrations WHERE migration = '2025_10_09_000001_improve_servicios_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000002_improve_products_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000003_improve_product_images_table';
DELETE FROM migrations WHERE migration = '2025_10_09_000005_optimize_users_table';

-- Verificar que se eliminaron correctamente
SELECT migration
FROM migrations
WHERE migration LIKE '%2025_10_09%';
-- Solo debe mostrar: 2025_10_09_000004_create_favorites_table

-- ----------------------------------------------------------------------------
-- 4. VERIFICACIÓN POST-LIMPIEZA
-- ----------------------------------------------------------------------------

-- Contar migraciones después de la limpieza
SELECT COUNT(*) as migraciones_restantes FROM migrations;
-- Debe mostrar: 23 (27 originales - 4 eliminadas)

-- Verificar que no hay referencias huérfanas
SELECT migration
FROM migrations
WHERE migration NOT IN (
    '0001_01_01_000000_create_users_table',
    '0001_01_01_000001_create_cache_table',
    '0001_01_01_000002_create_jobs_table',
    '2023_01_01_000000_create_roles_table',
    '2023_01_01_000003_create_entrepreneurs_table',
    '2023_01_01_000004_create_categories_table',
    '2023_01_01_000005_create_products_table',
    '2023_01_01_000006_create_product_categories_table',
    '2023_01_01_000008_create_carts_table',
    '2023_01_01_000010_create_orders_table',
    '2023_01_01_000011_create_order_items_table',
    '2023_01_01_000012_create_addresses_table',
    '2023_01_01_000013_create_shipments_table',
    '2023_01_01_000014_create_payments_table',
    '2023_01_01_000015_create_reviews_table',
    '2024_01_01_000001_add_sales_to_products_table',
    '2025_06_28_203102_create_user__roles_table',
    '2025_06_28_203622_create_cart__items_table',
    '2025_06_28_204818_create_personal_access_tokens_table',
    '2025_06_28_214649_create_product_images_table',
    '2025_08_10_212119_create_servicios_table',
    '2025_10_04_224422_add_avatar_to_entrepreneurs_table',
    '2025_10_04_234139_remove_avatar_from_entrepreneurs_table',
    '2025_10_09_000004_create_favorites_table'
);
-- No debe devolver ningún resultado

-- ----------------------------------------------------------------------------
-- 5. ROLLBACK (Solo si algo sale mal)
-- ----------------------------------------------------------------------------

-- En caso de necesitar restaurar la tabla migrations original:
-- DROP TABLE migrations;
-- CREATE TABLE migrations AS SELECT * FROM migrations_backup_20251009;

-- ----------------------------------------------------------------------------
-- 6. COMANDOS PARA VERIFICACIÓN EN PHP ARTISAN
-- ----------------------------------------------------------------------------

-- Después de ejecutar estos scripts SQL, verificar con:
-- php artisan migrate:status
-- No debe mostrar migraciones con estado "Migration not found"

-- ============================================================================
-- RESUMEN DE CAMBIOS
-- ============================================================================

-- ANTES:  27 migraciones en tabla migrations
-- DESPUÉS: 23 migraciones en tabla migrations
-- ELIMINADAS: 4 migraciones de corrección que fueron consolidadas
-- MANTENIDA: 1 migración nueva (create_favorites_table)

-- ============================================================================

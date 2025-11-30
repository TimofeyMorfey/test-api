# API Data Fetcher

Доступ к БД

https://www.mediafire.com/file/kve22bc5ksh5h81/api-test_%25281%2529.sql/file

# Таблицы

- Sales (данные о продажах)
- Orders (данные о заказах)
- Incomes (данные о доходах)
- Stocks (данные об остатках на складах)


# команды

- для получения данных о продажах

php artisan app:fet-sales --dateFrom=2025-01-01 --dateTo=2025-01-02

обязательные параметры
--dateFrom=
--dateTo=

- для получения данных о заказах

php artisan app:fetch-orders --dateFrom=2025-01-01 --dateTo=2025-01-02

обязательные параметры
--dateFrom=
--dateTo=

- для получения данных о доходах

php artisan app:fetch-incomes --dateFrom=2025-01-01 --dateTo=2025-01-02

обязательные параметры
--dateFrom=
--dateTo=

- для получения данных об остатках

php artisan app:fetch-stocks --dateFrom=2025-01-01

обязательные параметры
--dateFrom=(дата должна быть сегодняшняя) 

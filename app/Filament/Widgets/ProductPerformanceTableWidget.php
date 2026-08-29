<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMerchantProduct;
use App\Models\UserMerchant;
use App\Models\UserMerchantOrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductPerformanceTableWidget extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'جدول مقارنة أداء المنتجات بين التجار';

    protected static ?string $subheading = 'مقارنة مفصلة للمنتجات والأسعار والمبيعات';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $userId = Auth::user()->id;

        $aggregatesQuery = UserMerchantOrderItem::query()
            ->select('user_merchant_product_id')
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_quantity_sold')
            ->selectRaw('COALESCE(SUM(total_price), 0) as total_revenue')
            ->selectRaw('COUNT(DISTINCT user_merchant_order_id) as orders_count')
            ->groupBy('user_merchant_product_id');

        return $table
            ->query(
                UserMerchantProduct::query()
                    ->select([
                        'user_merchant_products.*',
                        'user_merchants.name as merchant_name',
                        DB::raw('COALESCE(product_aggregates.total_quantity_sold, 0) as total_quantity_sold'),
                        DB::raw('COALESCE(product_aggregates.total_revenue, 0) as total_revenue'),
                        DB::raw('COALESCE(product_aggregates.orders_count, 0) as orders_count'),
                    ])
                    ->join('user_merchants', 'user_merchant_products.user_merchant_id', '=', 'user_merchants.id')
                    ->leftJoinSub($aggregatesQuery, 'product_aggregates', function ($join) {
                        $join->on('user_merchant_products.id', '=', 'product_aggregates.user_merchant_product_id');
                    })
                    ->where('user_merchants.user_id', $userId)
                    ->where('user_merchants.is_active', true)
            )
            ->columns([
                Tables\Columns\TextColumn::make('merchant_name')
                    ->label('اسم التاجر')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->formatStateUsing(fn($state) => is_string($state) ? $state : 'غير محدد'),

                Tables\Columns\TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->formatStateUsing(fn($state) => is_string($state) ? $state : 'غير محدد'),

                Tables\Columns\TextColumn::make('barcode')
                    ->label('الباركود')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => is_string($state) ? $state : 'غير محدد'),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')

                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('total_quantity_sold')
                    ->label('الكمية المباعة')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('إجمالي الإيرادات')

                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('orders_count')
                    ->label('عدد الطلبات')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('merchant_name')
                    ->label('التاجر')
                    ->options(
                        UserMerchant::where('user_id', $userId)
                            ->where('is_active', true)
                            ->pluck('name', 'name')
                    ),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('المنتجات النشطة')
                    ->boolean()
                    ->trueLabel('نشط فقط')
                    ->falseLabel('غير نشط فقط')
                    ->native(false),
            ])
            ->actions([
                // يمكن إضافة actions هنا لاحقاً
            ])
            ->bulkActions([
                // يمكن إضافة bulk actions هنا لاحقاً
            ])
            ->defaultSort('total_revenue', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}


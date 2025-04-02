<?php
namespace App\Services;

use App\Models\Price;

class BookingPriceService
{
    /**
     * Calculate the price for a booking based on the given from and to dates.
     *
     * @param string $from
     * @param string $to
     * @return float
     */
    public function calculatePrice(string $from, string $to): float
    {
        $fromDate = \Carbon\Carbon::parse($from);
        $toDate = \Carbon\Carbon::parse($to);

        $totalPrice = 0.0;

        // Loop the dates to calculate the total price
        while ($fromDate <= $toDate) {
            $price = $this->getPriceForDate($fromDate);
            $totalPrice += $price;
            $fromDate->addDay();
        }

        return $totalPrice;
    }

    /**
     * Get the price for a specific date based on the season table.
     *
     * @param \Carbon\Carbon $date
     * @return float
     */
    private function getPriceForDate(\Carbon\Carbon $date): float
    {
        $price = Price::where('from', '<=', $date->toDateString())
                      ->where('to', '>=', $date->toDateString())
                      ->first();

        return $price ? $price->price : 0.0;
    }
}

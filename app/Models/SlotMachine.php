<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlotMachine extends Model
{
    protected $fillable = ['name', 'configuration'];

    protected $casts = [
        'configuration' => 'array',
    ];

    /**
     * Get the list of all items in this slot machine
     */
    public function getItemsAttribute()
    {
        return array_keys($this->configuration);
    }

    /**
     * Get the winning items (items with odds configured)
     */
    public function getWinningItemsAttribute()
    {
        return collect($this->configuration)
            ->filter(function ($config, $item) {
                return is_array($config) && isset($config['odds']);
            })
            ->sortBy(function ($config) {
                return $config['order'] ?? 999;
            })
            ->toArray();
    }

    /**
     * Get the regular items (items without odds configured)
     */
    public function getRegularItemsAttribute()
    {
        return collect($this->configuration)
            ->filter(function ($config, $item) {
                return ! is_array($config);
            })
            ->toArray();
    }

    /**
     * Get configuration for a specific item
     */
    public function getItemConfig($item)
    {
        return $this->configuration[$item] ?? null;
    }

    /**
     * Check if an item is a winning item
     */
    public function isWinningItem($item)
    {
        $config = $this->getItemConfig($item);

        return is_array($config) && isset($config['odds']);
    }

    /**
     * Get odds for a winning item
     */
    public function getItemOdds($item)
    {
        $config = $this->getItemConfig($item);

        return $config['odds'] ?? null;
    }

    /**
     * Get prize for a winning item
     */
    public function getItemPrize($item)
    {
        $config = $this->getItemConfig($item);

        return $config['prize'] ?? null;
    }
}

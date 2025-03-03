<?php

namespace App\Livewire;

use App\Models\SlotMachine;
use App\Models\SlotMachine as SlotMachineModel;
use Illuminate\Support\Lottery;
use Livewire\Attributes\Url;
use Livewire\Component;

class Machine extends Component
{
    public $spinning = false;

    public $message = '';

    public $messageClass = '';

    public $tier = null;

    public $availableSpins = 0;

    public $noSpinsMessage = 'You have no spins available. Get more spins to play!';

    public SlotMachine $slotMachine;

    public $items = [];

    public $winningItems = [];

    public $selectedMachine;


    public function mount(SlotMachine $slotMachine)
    {
        $this->createMachine();
    }

    public function updated($property, $value)
    {
        if ($property === 'selectedMachine') {
            $this->redirectRoute('slots', $value);
        }
    }

    public function createMachine()
    {
       $this->selectedMachine = $this->slotMachine->name;
        $this->items = $this->slotMachine->items;
        $this->winningItems = $this->slotMachine->winningItems;

        $this->refreshAvailableSpins();
    }

    public function refreshAvailableSpins()
    {
        $this->availableSpins = 10000000;
    }

    public function spin()
    {
        if ($this->spinning) {
            return;
        }

        $this->spinning = true;
        $this->message = '';
        $this->messageClass = '';
        $this->tier = null;

        $outcome = 'lose';
        $winningItem = null;

        // Process each winning item in order
        foreach ($this->winningItems as $item => $config) {
            if (! isset($config['odds']) || ! is_array($config['odds']) || count($config['odds']) !== 2) {
                continue;
            }

            $odds = $config['odds'];

            Lottery::odds($odds[0], $odds[1])
                ->winner(function () use (&$outcome, &$winningItem, $item, $config) {
                    $outcome = 'win';
                    $winningItem = $item;
                    $this->tier = $config['order'] ?? null;

                    // If we found a winner, stop checking other items
                    return false;
                })
                ->choose();

            // If we already have a winner, stop processing
            if ($outcome === 'win') {
                break;
            }
        }

        $resultIndices = $this->generateSlotResults($outcome, $winningItem);

        // Convert indices to actual item names
        $resultItems = array_map(function ($index) {
            return $this->items[$index] ?? 'unknown';
        }, $resultIndices);



        $prizeDetails = null;
        $prizeName = null;

        if ($outcome === 'win' && $winningItem) {
            $prize = $this->slotMachine->getItemPrize($winningItem);
            $tier = $this->slotMachine->getItemConfig($winningItem)['order'] ?? 1;

            if ($tier === 1) {
                $this->message = "🎉 JACKPOT! You won {$prize}! 🎉";
                $this->messageClass = 'win jackpot';
            } else {
                $this->message = "🎉 CONGRATULATIONS! You won {$prize}! 🎉";
                $this->messageClass = 'win';
            }

            $prizeDetails = json_encode(['name' => $prize]);
            $prizeName = $prize;
        } else {
            $this->message = 'Try Again!';
            $this->messageClass = '';
        }

        $this->refreshAvailableSpins();

        $this->dispatch('spin-results', [
            [
                'results' => $resultIndices,
                'outcome' => $outcome,
                'tier' => $this->tier,
                'prize' => $prizeDetails ? json_decode($prizeDetails, true) : null,
            ],
        ]);
    }

    protected function generateSlotResults($outcome, $winningItem = null)
    {
        $results = [];
        $itemCount = count($this->items);

        if ($outcome === 'win' && $winningItem) {
            $winningIndex = array_search($winningItem, $this->items);

            // All five slots show the winning item
            for ($i = 0; $i < 5; $i++) {
                $results[$i] = $winningIndex;
            }
        } else {
            $usedIndices = [];
            for ($i = 0; $i < 5; $i++) {
                do {
                    $index = rand(0, $itemCount - 1);

                    // Never show winning items for a loss
                    $item = $this->items[$index] ?? null;
                    if ($item && $this->slotMachine->isWinningItem($item)) {
                        continue;
                    }
                } while (in_array($index, $usedIndices) && count($usedIndices) < $itemCount - count($this->winningItems));

                $results[$i] = $index;
                $usedIndices[] = $index;
            }
        }

        return $results;
    }

    public function spinComplete()
    {
        $this->spinning = false;
    }
    public function render()
    {
        return view('livewire.machine');
    }
}

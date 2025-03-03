<div>
    <div>
        <div class="available-spins">
            <span class="spins-count">Available Spins: {{ $availableSpins }}</span>
            @if($availableSpins <= 0)
                <div class="no-spins-warning">{{ $noSpinsMessage }}</div>
            @endif

        </div>
        <div class="flex items-center justify-center">
            <select wire:model.live="selectedMachine" class="my-4 bg-white px-4 py-2 rounded-md shadow-sm text-sm font-medium text-gray-900 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                @foreach(\App\Models\SlotMachine::all() as $machine)
                    <option value="{{ $machine->name }}">{{ str($machine->name)->title() }}</option>
                @endforeach
            </select>
        </div>
        

        <div class="slot-machine" >
            <div wire:ignore>
                <div class="slot-container row">
                    <div class="slot col-xs-2">
                        <div id="slot1-inner" class="slot-inner"></div>
                    </div>
                    <div class="slot col-xs-2">
                        <div id="slot2-inner" class="slot-inner"></div>
                    </div>
                    <div class="slot col-xs-2">
                        <div id="slot3-inner" class="slot-inner"></div>
                    </div>
                    <div class="slot col-xs-2">
                        <div id="slot4-inner" class="slot-inner"></div>
                    </div>
                    <div class="slot col-xs-2">
                        <div id="slot5-inner" class="slot-inner"></div>
                    </div>
                </div>
            </div>
        </div>

        <button
            id="spin-button"
            class="btn btn-danger btn-lg spin-button"
            wire:click="spin"
            wire:loading.attr="disabled"
            {{ $spinning ? 'disabled' : '' }}>
            SPIN!
        </button>

        <div id="result" class="result {{ $messageClass }}">
            {{ $message }}
        </div>
    </div>

    <style>
        .available-spins {
            text-align: center;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
        }

        .spins-count {
            display: inline-block;
            padding: 5px 15px;
            background-color: #333;
            color: #fff;
            border-radius: 20px;
        }

        .no-spins-warning {
            color: #ff3333;
            margin-top: 5px;
        }

        .result.error {
            color: #ff3333;
        }

        .slot-machine {
            max-width: 800px;
            margin: 0 auto;
            background-color: #222;
            border: 8px solid gold;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            position: relative;
            overflow: hidden;
        }

        .slot-container {
            background-color: #111;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            height: auto; /* Remove fixed height */
            min-height: 250px; /* Set minimum height */
            max-height: 380px; /* Set maximum height */
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 10; /* Ensure reels are above effects */
        }

        /* Make container responsive to screen size */
        @media (max-width: 768px) {
            .slot-container {
                min-height: 200px;
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .slot-container {
                min-height: 150px;
                max-height: 250px;
            }
        }

        .slot {
            position: relative;
            width: 19%;
            height: auto; /* Remove fixed height */
            aspect-ratio: 2/5; /* Maintain proportion */
            min-height: 150px; /* Minimum height */
            max-height: 350px; /* Maximum height */
            overflow: hidden;
            border: 2px solid gold;
            border-radius: 5px;
            background-color: white;
            padding: 0;
        }

        .slot-inner {
            position: absolute;
            top: 0;
            width: 100%;
            backface-visibility: hidden; /* Prevent flicker */
            will-change: transform, top; /* Optimize for animation */
            transform: translateZ(0); /* Force GPU acceleration */
        }

        .slot-item {
            width: 100%;
            /* Height will be set dynamically by JavaScript */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            backface-visibility: hidden; /* Prevent flicker */
        }

        .slot-item img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }

        .spin-button {
            background-color: #FF0000;
            color: white;
            font-size: clamp(18px, 5vw, 24px); /* Responsive font size */
            padding: clamp(10px, 3vw, 15px) clamp(20px, 5vw, 40px); /* Responsive padding */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin: 20px auto;
            display: block;
            position: relative;
            z-index: 20; /* Ensure button is clickable */
        }

        .spin-button:hover {
            background-color: #cc0000;
        }

        .spin-button:disabled {
            background-color: #666;
            cursor: not-allowed;
        }

        .result {
            text-align: center;
            font-size: clamp(18px, 5vw, 24px); /* Responsive font size */
            font-weight: bold;
            min-height: 36px;
            margin-top: 20px;
            position: relative;
            z-index: 20; /* Ensure message is visible */
            opacity: 0; /* Start hidden */
            transition: opacity 0.5s ease-in; /* Smooth fade-in transition */
        }

        .win {
            color: gold;
            animation: winPulse 1s infinite;
        }

        .jackpot {
            color: #FFC107; /* Brighter gold color */
            text-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700, 0 0 30px #FF4500;
            font-size: clamp(22px, 6vw, 28px); /* Larger font for jackpot */
            animation: jackpotPulse 0.8s infinite;
        }

        @keyframes winPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes jackpotPulse {
            0% {
                transform: scale(1);
                text-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700, 0 0 30px #FF4500;
            }
            50% {
                transform: scale(1.15);
                text-shadow: 0 0 15px #FFD700, 0 0 25px #FFD700, 0 0 35px #FF4500;
            }
            100% {
                transform: scale(1);
                text-shadow: 0 0 10px #FFD700, 0 0 20px #FFD700, 0 0 30px #FF4500;
            }
        }

        /* Jackpot win animation for slot machine */
        .jackpot-win-animation {
            animation: jackpotBorder 1s infinite;
        }

        @keyframes jackpotBorder {
            0% {
                border-color: #FF4500;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            }
            50% {
                border-color: #FFD700;
                box-shadow: 0 0 30px rgba(255, 215, 0, 0.8);
            }
            100% {
                border-color: #FF4500;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            }
        }

        /* Regular win animation */
        .win-animation {
            animation: winBorder 1.5s ease-in-out infinite;
        }

        @keyframes winBorder {
            0% {
                border-color: #FF4500;
            }
            50% {
                border-color: #FFD700;
            }
            100% {
                border-color: #FF4500;
            }
        }

        /* Responsive title */
        h1.text-center {
            font-size: clamp(24px, 5vw, 36px);
        }

        /* Make the entire slot machine more compact on small screens */
        @media (max-width: 576px) {
            .slot-machine {
                padding: 10px;
                border-width: 5px;
            }

            .slot-container {
                padding: 5px;
            }

            .slot {
                border-width: 1px;
            }
        }

        .jackpot-win-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.2) 0%, rgba(255, 215, 0, 0) 70%);
            pointer-events: none;
            z-index: 5;
            animation: jackpotGlow 2s infinite;
        }

        @keyframes jackpotGlow {
            0% {
                opacity: 0.2;
            }
            50% {
                opacity: 0.8;
            }
            100% {
                opacity: 0.2;
            }
        }

        @keyframes messageReveal {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result.show-message {
            animation: messageReveal 0.5s ease forwards;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const items = @json($items);
            const imagePath = '/images/slots/{{$slotMachine->name}}/';

            let isSpinning = false;
            let completedReels = 0;

            const slots = [];
            for (let i = 1; i <= 5; i++) {
                slots.push(document.getElementById(`slot${i}-inner`));
            }

            let itemHeight;
            let slotHeight;

            function calculateDimensions() {
                const slotContainer = document.querySelector('.slot-container');
                const slot = document.querySelector('.slot');

                if (!slotContainer || !slot) return;

                slotHeight = slot.offsetHeight;
                itemHeight = Math.floor(slotHeight * 0.4);
                itemHeight = Math.max(60, Math.min(150, itemHeight));

                const style = document.createElement('style');
                style.innerHTML = `.slot-item { height: ${itemHeight}px; }`;

                const oldStyle = document.getElementById('dynamic-slot-styles');
                if (oldStyle) {
                    oldStyle.remove();
                }

                style.id = 'dynamic-slot-styles';
                document.head.appendChild(style);

                return {slotHeight, itemHeight};
            }

            calculateDimensions();

            window.addEventListener('resize', function () {
                if (!isSpinning) {
                    calculateDimensions();
                    initializeReels();
                }
            });

            function initializeReels() {
                const dimensions = calculateDimensions();
                const currentItemHeight = dimensions.itemHeight;

                // Clear all slots first
                slots.forEach(slot => {
                    if (!slot) return;
                    slot.innerHTML = '';
                });

                // Create a standard sequence of items for all reels
                const standardSequence = [];
                // Generate several sets of items to ensure we have enough
                for (let j = 0; j < 15; j++) {
                    items.forEach((item, index) => {
                        const div = document.createElement('div');
                        div.className = 'slot-item';
                        div.dataset.index = index;

                        const img = document.createElement('img');
                        img.src = `${imagePath}${item}.png`;
                        img.alt = item;
                        img.title = item;
                        img.onerror = function () {
                            this.src = `${imagePath}1.png`;
                        };

                        div.appendChild(img);
                        standardSequence.push(div.outerHTML);
                    });
                }

                // Now populate all reels with the same sequence
                slots.forEach((slot, slotIndex) => {
                    if (!slot) return;

                    // Use the same HTML for all reels to ensure alignment
                    slot.innerHTML = standardSequence.join('');

                    // Start each reel at a different but aligned position
                    const itemsPerSet = items.length;
                    const randomSetIndex = Math.floor(Math.random() * 5);
                    const randomItemIndex = Math.floor(Math.random() * itemsPerSet);

                    // Calculate an aligned starting position
                    const alignedPosition = -((randomSetIndex * itemsPerSet + randomItemIndex) * currentItemHeight) - (currentItemHeight / 3);

                    // Apply the position
                    slot.style.transition = 'none';
                    slot.style.top = alignedPosition + 'px';
                });
            }

            initializeReels();

            // Rest of the existing JavaScript (spinReel, handleSpin, etc.)
            function spinReel(reel, targetIndex, delay) {
                initializeReels();
                return new Promise(resolve => {
                    setTimeout(() => {
                        normalizePositions();

                        const dimensions = calculateDimensions();
                        const currentItemHeight = dimensions.itemHeight;
                        const currentSlotHeight = dimensions.slotHeight;

                        const currentTop = parseInt(reel.style.top) || 0;

                        const itemSetHeight = items.length * currentItemHeight;
                        const scrollDistance = itemSetHeight * 4;

                        const currentPositionInSet = currentTop % itemSetHeight;

                        const itemOffset = targetIndex * currentItemHeight;
                        const targetPositionInSet = -itemOffset;

                        const alignmentOffset = targetPositionInSet - currentPositionInSet;

                        const centeringOffset = Math.floor((currentSlotHeight - currentItemHeight) / 2);

                        const targetPosition = currentTop - scrollDistance + alignmentOffset + centeringOffset;

                        reel.style.transition = 'none';
                        void reel.offsetHeight;

                        const spinDuration = 2; // seconds

                        reel.style.transition = `top ${spinDuration}s cubic-bezier(0.1, 0.2, 0.3, 1)`;
                        reel.style.top = targetPosition + 'px';

                        function onTransitionEnd() {
                            completedReels++;
                            reel.removeEventListener('transitionend', onTransitionEnd);
                            resolve();
                        }

                        reel.addEventListener('transitionend', onTransitionEnd, {once: true});
                    }, delay);
                });
            }

            async function handleSpin(details) {
                try {
                    const resultElement = document.getElementById('result');
                    if (resultElement) {
                        resultElement.style.opacity = '0';
                        resultElement.style.transition = 'opacity 0.3s ease';
                    }

                    const spinButton = document.getElementById('spin-button');
                    if (spinButton) {
                        spinButton.disabled = true;
                    }

                    isSpinning = true;
                    completedReels = 0;

                    const baseDelay = 200;
                    const spinPromises = [];

                    details.results.forEach((targetIndex, index) => {
                        const delay = index * baseDelay;
                        spinPromises.push(spinReel(slots[index], targetIndex, delay));
                    });

                    await Promise.all(spinPromises);

                    // Add a small delay before showing effects and message
                    setTimeout(() => {
                        if (details.outcome !== 'lose') {
                            addWinEffects();
                        }

                        // Force the result element to be visible
                        if (resultElement) {
                            resultElement.style.opacity = '1';
                            resultElement.classList.add('show-message');
                        }

                        @this.spinComplete();

                        setTimeout(() => {
                            isSpinning = false;

                            if (spinButton) {
                                spinButton.disabled = false;
                            }
                        }, 1000);
                    }, 500);

                } catch (error) {
                    console.error('Error during spin:', error);
                    isSpinning = false;

                    const spinButton = document.getElementById('spin-button');
                    if (spinButton) {
                        spinButton.disabled = false;
                    }

                    @this.spinComplete();
                }
            }

            function normalizePositions() {
                slots.forEach(slot => {
                    if (!slot) return;

                    const currentTop = parseInt(slot.style.top) || 0;
                    const dimensions = calculateDimensions();
                    const itemSetHeight = items.length * dimensions.itemHeight;

                    if (Math.abs(currentTop) > itemSetHeight * 20) {
                        const normalizedPosition = currentTop % itemSetHeight;
                        const newPosition = normalizedPosition - (itemSetHeight * 10);

                        slot.style.transition = 'none';
                        slot.style.top = newPosition + 'px';
                    }
                });
            }

            function addWinEffects() {
                const slotMachine = document.querySelector('.slot-machine');
                const resultElement = document.getElementById('result');

                slotMachine.classList.add('win-animation');

                // Ensure the message is visible
                if (resultElement) {
                    resultElement.style.opacity = '1';
                    resultElement.classList.add('show-message');
                }

                setTimeout(() => {
                    slotMachine.classList.remove('win-animation');
                }, 5000);
            }

            window.addEventListener('spin-results', function (event) {
                try {
                    const details = event.detail[0][0];
                    handleSpin(details);
                } catch (error) {
                    console.error('Error processing spin results:', error);
                }
            });


        });


    </script>
</div>

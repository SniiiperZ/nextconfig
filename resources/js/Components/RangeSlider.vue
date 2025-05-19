<script setup>
const props = defineProps({
    modelValue: {
        type: [Number, String],
        required: true,
    },
    min: {
        type: Number,
        default: 0,
    },
    max: {
        type: Number,
        default: 100,
    },
    step: {
        type: Number,
        default: 1,
    },
    label: {
        type: String,
        required: true,
    },
    error: {
        type: String,
        default: "",
    },
    showMarks: {
        type: Boolean,
        default: true,
    },
    markSteps: {
        type: Number,
        default: 5,
    },
});

const emit = defineEmits(["update:modelValue"]);

const updateValue = (event) => {
    emit("update:modelValue", Number(event.target.value));
};

// Calculer le pourcentage de la valeur actuelle pour l'affichage du gradient
const calculatePercentage = () => {
    return ((props.modelValue - props.min) / (props.max - props.min)) * 100;
};

// Générer des repères visibles pour le slider
const generateMarks = () => {
    const marks = [];
    const range = props.max - props.min;
    const totalMarks = Math.floor(range / (props.markSteps * props.step)) + 1;

    for (let i = 0; i < totalMarks; i++) {
        const value = props.min + i * props.markSteps * props.step;
        const percentage = ((value - props.min) / range) * 100;

        // Ne pas ajouter de marque pour les valeurs min et max (déjà affichées comme texte)
        if (value !== props.min && value !== props.max) {
            marks.push({
                value,
                percentage,
                isCurrentValue: props.modelValue === value,
            });
        }
    }

    return marks;
};
</script>

<template>
    <div class="mb-6 slider-container">
        <div class="flex justify-between items-center mb-2">
            <label class="text-white text-sm font-medium flex items-center">
                {{ label }}
            </label>
            <span class="text-led-green font-medium value-display">
                {{ modelValue }} €
            </span>
        </div>

        <div class="slider-track-container">
            <div
                class="h-full bg-gradient-to-r from-gaming-red to-led-green rounded-full transition-all duration-300"
                :style="{ width: `${calculatePercentage()}%` }"
            ></div>

            <!-- Curseur visible -->
            <div
                class="slider-thumb"
                :style="{ left: `${calculatePercentage()}%` }"
            ></div>

            <input
                type="range"
                :min="min"
                :max="max"
                :step="step"
                :value="modelValue"
                @input="updateValue"
                class="slider-input"
                list="budget-marks"
            />
            <datalist id="budget-marks">
                <option
                    v-for="(mark, index) in generateMarks()"
                    :key="index"
                    :value="mark.value"
                ></option>
            </datalist>
        </div>

        <div class="mt-3 relative">
            <!-- Affichage des valeurs principales en dessous du slider -->
            <div class="flex justify-between text-xs text-white/60">
                <span>{{ min }}€</span>
                <span>{{ max }}€</span>
            </div>

            <!-- Marques intermédiaires avec valeurs -->
            <div v-if="showMarks" class="marks-container mt-1">
                <div
                    v-for="mark in generateMarks()"
                    :key="mark.value"
                    class="mark-wrapper"
                    :class="{ 'mark-active': mark.isCurrentValue }"
                    :style="{ left: `${mark.percentage}%` }"
                >
                    <div class="mark"></div>
                    <div class="mark-value">{{ mark.value }}€</div>
                </div>
            </div>
        </div>

        <p v-if="error" class="mt-1 text-sm text-gaming-red">{{ error }}</p>
    </div>
</template>

<style scoped>
.slider-container {
    position: relative;
}

.slider-track-container {
    position: relative;
    height: 8px;
    width: 100%;
    border-radius: 20px;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(236, 64, 122, 0.3);
    overflow: visible; /* Changé à visible pour afficher le curseur */
    margin: 10px 0;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
}

/* Style du curseur visible */
.slider-thumb {
    position: absolute;
    top: 50%;
    width: 16px;
    height: 16px;
    background: white;
    border: 2px solid #ec407a;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 8px rgba(236, 64, 122, 0.7);
    pointer-events: none; /* Pour ne pas interférer avec les interactions */
    z-index: 10;
    transition: transform 0.1s ease, box-shadow 0.2s ease;
}

/* Animation au survol du slider */
.slider-container:hover .slider-thumb {
    transform: translate(-50%, -50%) scale(1.2);
    box-shadow: 0 0 12px rgba(236, 64, 122, 0.9),
        0 0 4px rgba(255, 255, 255, 0.9);
}

.slider-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    margin: 0;
    z-index: 2;
}

/* Style pour le curseur personnalisé natif (invisible car nous utilisons notre propre curseur) */
.slider-input::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: transparent;
    cursor: pointer;
}

.slider-input::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: transparent;
    border: none;
    cursor: pointer;
}

/* Style pour l'affichage de la valeur */
.value-display {
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 255, 85, 0.4);
    padding: 2px 8px;
    border-radius: 20px;
    min-width: 70px;
    text-align: center;
    box-shadow: 0 0 8px rgba(0, 255, 85, 0.3);
    font-family: "Play", sans-serif;
    transition: all 0.2s ease;
}

.slider-container:hover .value-display {
    background: rgba(0, 0, 0, 0.5);
    box-shadow: 0 0 12px rgba(0, 255, 85, 0.5);
    transform: translateY(-1px);
}

/* Styles pour les marques sur le slider */
.marks-container {
    position: relative;
    width: 100%;
    height: 25px;
}

.mark-wrapper {
    position: absolute;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 1px;
}

.mark {
    height: 8px;
    width: 2px;
    background-color: rgba(236, 64, 122, 0.5);
    margin-bottom: 4px;
}

.mark-value {
    font-size: 10px;
    color: rgba(255, 255, 255, 0.6);
    white-space: nowrap;
}

.mark-active .mark {
    background-color: #00ff55;
    height: 10px;
    width: 3px;
    box-shadow: 0 0 5px #00ff55;
}

.mark-active .mark-value {
    color: #00ff55;
    font-weight: bold;
}
</style>

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
        default: false,
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
            <input
                type="range"
                :min="min"
                :max="max"
                :step="step"
                :value="modelValue"
                @input="updateValue"
                class="slider-input"
            />
        </div>

        <div class="flex justify-between mt-1 text-xs text-white/60 relative">
            <span>{{ min }}€</span>

            <!-- Marks optionnels -->
            <div v-if="showMarks" class="marks-container">
                <div
                    v-for="i in Math.floor((max - min) / (markSteps * step))"
                    :key="i"
                    class="mark"
                    :style="{
                        left: `${
                            ((i * markSteps * step) / (max - min)) * 100
                        }%`,
                    }"
                ></div>
            </div>

            <span>{{ max }}€</span>
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
    overflow: hidden;
    margin: 10px 0;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4);
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

/* Style pour le curseur personnalisé */
.slider-input::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ec407a;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.7);
    transition: all 0.2s ease;
}

.slider-input::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ec407a;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 0 10px rgba(236, 64, 122, 0.7);
    transition: all 0.2s ease;
}

/* État au survol/focus */
.slider-input:hover::-webkit-slider-thumb {
    transform: scale(1.1);
    background: #ff0055;
    box-shadow: 0 0 15px rgba(255, 0, 85, 0.9);
}

.slider-input:hover::-moz-range-thumb {
    transform: scale(1.1);
    background: #ff0055;
    box-shadow: 0 0 15px rgba(255, 0, 85, 0.9);
}

.slider-input:focus::-webkit-slider-thumb {
    transform: scale(1.1);
    background: #ff0055;
    box-shadow: 0 0 15px rgba(255, 0, 85, 0.9), 0 0 0 3px rgba(0, 255, 85, 0.3);
}

.slider-input:focus::-moz-range-thumb {
    transform: scale(1.1);
    background: #ff0055;
    box-shadow: 0 0 15px rgba(255, 0, 85, 0.9), 0 0 0 3px rgba(0, 255, 85, 0.3);
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
    position: absolute;
    top: -8px;
    left: 0;
    right: 0;
    height: 8px;
    pointer-events: none;
}

.mark {
    position: absolute;
    width: 1px;
    height: 6px;
    background-color: rgba(255, 255, 255, 0.3);
    transform: translateX(-50%);
}
</style>

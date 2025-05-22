<template>
    <div class="md:col-span-1 flex justify-between">
        <div class="px-4 sm:px-0">
            <h3 class="text-xl font-play font-medium text-led-green">
                <slot name="title" />
            </h3>

            <p class="mt-1 text-sm text-white/70">
                <slot name="description" />
            </p>
        </div>

        <div class="px-4 sm:px-0">
            <slot name="aside" />
        </div>
    </div>
</template>

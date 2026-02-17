<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { computed } from 'vue';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        name?: string;
        modelValue?: boolean;
        class?: HTMLAttributes['class'];
        disabled?: boolean;
    }>(),
    {
        modelValue: false,
        disabled: false,
    }
);

const emits = defineEmits<{
    (e: 'update:modelValue', payload: boolean): void;
}>();

const isChecked = computed({
    get: () => props.modelValue,
    set: (value: boolean) => emits('update:modelValue', value),
});

const handleChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    isChecked.value = target.checked;
};
</script>

<template>
    <label
        class="relative inline-flex cursor-pointer items-center"
        :class="{ 'cursor-not-allowed opacity-50': disabled }"
    >
        <input
            type="checkbox"
            :name="name"
            :checked="modelValue"
            :disabled="disabled"
            value="1"
            class="peer sr-only"
            @change="handleChange"
        />
        <div
            :class="
                cn(
                    'relative h-6 w-11 rounded-full border border-gray-300 bg-gray-200 shadow-inner transition-all after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[\'\'] peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/30 peer-checked:bg-primary',
                    props.class
                )
            "
        />
    </label>
</template>

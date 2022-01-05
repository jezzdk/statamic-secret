<template>
    <div>
        <text-input v-if="type === 'single'" type="text" :value="value" @input="updateDebounced" />
        <textarea-input v-if="type === 'multi'" :value="value" @input="updateDebounced" />
    </div>
</template>

<script>
export default {
    mixins: [Fieldtype],
    data() {
        return {
            type: null,
        }
    },
    mounted () {
        this.type = this.config.input_type || 'single'
    },
};
</script>

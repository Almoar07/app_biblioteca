@props([
    'data' => '[]', // JSON de objetos con 'id' y 'label'
    'placeholder' => 'Buscar...',
    'emit' => null, // Nombre del evento Livewire a emitir (opcional)
])

<div x-data="combobox({{ $data }}, '{{ $emit }}')" class="relative w-full" wire:ignore>
    <input x-model="query" @input="filter()" @keydown.arrow-down.prevent="navigate(1)"
        @keydown.arrow-up.prevent="navigate(-1)" @keydown.enter.prevent="select()" @click="open = true"
        @click.away="open = false" type="text"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-indigo-200"
        :placeholder="'{{ $placeholder }}'">

    <ul x-show="open" x-transition
        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
        <template x-for="(item, index) in filtered" :key="item.id">
            <li :class="{
                'bg-indigo-600 text-white': index === selectedIndex,
                'hover:bg-indigo-100': index !== selectedIndex
            }"
                class="px-4 py-2 cursor-pointer" @click="choose(item)" @mouseenter="selectedIndex = index">
                <span x-text="item.label"></span>
            </li>
        </template>

        <li x-show="filtered.length === 0" class="px-4 py-2 text-gray-500">Sin resultados</li>
    </ul>
</div>

<script>
    function combobox(data, emitEvent = null) {
        return {
            query: '',
            open: false,
            selectedIndex: 0,
            items: data,
            filtered: [],
            filter() {
                this.filtered = this.items.filter(item =>
                    item.label.toLowerCase().includes(this.query.toLowerCase())
                );
                this.open = true;
                this.selectedIndex = 0;
            },
            navigate(dir) {
                if (!this.open || this.filtered.length === 0) return;
                const count = this.filtered.length;
                this.selectedIndex = (this.selectedIndex + dir + count) % count;
            },
            select() {
                if (!this.open) return;
                this.choose(this.filtered[this.selectedIndex]);
            },
            choose(item) {
                this.query = item.label;
                this.open = false;

                if (emitEvent) {
                    Livewire.emit(emitEvent, item.id);
                }
            }
        }
    }
</script>

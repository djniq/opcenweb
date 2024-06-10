<template>
    <div>
        <!-- input search box for google places autocomplete -->
        <div class="search-box relative z-0 w-full mb-5 group">
            <GMapAutocomplete
                placeholder=""
                @place_changed="setPlace"
                :value="(address ? address.formatted_address : null) || (formAddress ? formAddress.formatted_address : null) || null"
                id="floating-address-selection"
                :options="autoCompleteOptions"
                class="block mb-2 py-2.5 px-0 w-full text-sm border-0 border-b-2 appearance-none bg-transparent text-white border-gray-600 focus:border-yellow-500 focus:outline-none focus:ring-0 peer"
                required>
            </GMapAutocomplete>
            <label for="floating-address-selection"
                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-yellow-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
            >Address</label>
        </div>
    </div>
</template>
  
  
<script lang="ts" setup>
import { PropType, computed, ref, watch } from 'vue';

const props = defineProps({
    formAddress: {
        type: [Object, null] as PropType<google.maps.Map | null>,
        default: null
    }
})

// Autocomplete options
const autoCompleteOptions = {
    componentRestrictions: {country: "ph"},
    fields: ['geometry', 'formatted_address', 'address_components']
}

const emit = defineEmits<{
  (e: "update:formAddress", value: google.maps.Map | null): void;
}>();

const address = computed<google.maps.Map | null>({
    get() {
        return props.formAddress;
    },
    set(newValue) {
        emit("update:formAddress", newValue);
    }
})

// Set the location based on the place selected
const setPlace = (place) => {
    address.value = place.name !== '' ? place: null;
}

</script>

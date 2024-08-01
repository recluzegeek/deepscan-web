<template>
    <div class="flex items-center justify-between w-full text-sm my-1">
        <div class="flex items-center gap-2 flex-grow">
            <img
                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAoElEQVR4nO2V0QnDIBCGvzxkATNG5khW6AadQGgKPviYXeIMHaRrdACLcHkptD0iWgr+cHjID5934glNmRoAC1wOhAWMBnIVYw/cgUn2A7BIntZN8kl8vRxw93yUF/M3SJB8foF4LSRmhG+QWLNdI3ArDUnqgDPwKAnZdfrrSsYadxLbY/xZu0zGqPfa/yRn1C8aSKrCAeuBcNqfsYl3egKac+n/ekuWvQAAAABJRU5ErkJggg=="
                alt="File Icon" class="w-8 h-8"/>
            <span class="font-mono font-medium">{{ name }}</span>
        </div>
        <span @click="emitRemoveFile" class="cursor-pointer text-red-500 hover:fill-red-500"
              v-if="!is_form_processing">
                                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="26" height="26"
                                     viewBox="0,0,300,150">
                                    <g fill="#ff0000">
                                        <g transform="scale(3.55556,3.55556)">
                                            <path
                                                d="M32,13c-1.105,0 -2,0.895 -2,2v1h-13c-2.209,0 -4,1.791 -4,4c0,1.97365 1.43236,3.60263 3.3125,3.92969l2.39453,28.73437c0.346,4.147 3.81361,7.33594 7.97461,7.33594h18.63672c4.161,0 7.62761,-3.18894 7.97461,-7.33594l2.39453,-28.73437c1.88014,-0.32705 3.3125,-1.95604 3.3125,-3.92969c0,-2.209 -1.791,-4 -4,-4h-13v-1c0,-1.105 -0.895,-2 -2,-2zM24.34766,24h23.30469l-2.25586,27.08203c-0.044,0.518 -0.47805,0.91797 -0.99805,0.91797h-16.79688c-0.52,0 -0.95409,-0.39997 -0.99609,-0.91797z"></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
    </div>

</template>

<script setup>
import {ref} from "vue";

const props = defineProps({
    name: String,
    is_form_processing: Boolean,
});

const emit = defineEmits(['remove-file'])
const index = ref()

const emitRemoveFile = () => {
    emit('remove-file', index.value);
};

</script>

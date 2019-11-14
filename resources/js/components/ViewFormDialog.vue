<template>
    <div>
        <p class="my-4">{{form.name}}</p>
        <b-list-group v-if="form.files.length">
            <b-list-group-item v-for="(file, index) in form.files" v-bind:key="file.id">
                {{file.title}}
                <b-button 
                    variant="primary" 
                    class="float-right" 
                    size="sm" 
                    :href="'/api/file/view/'+file.id" 
                    target="_blank"
                    >
                    View
                </b-button>
            </b-list-group-item>
        </b-list-group>
        <b-alert variant="warning" show v-else>No files added to form</b-alert>
    </div>
</template>

<script>
    export default {
        props:['form'],
    }
</script>

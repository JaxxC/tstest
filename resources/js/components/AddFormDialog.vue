<template>
    <div>
        <b-form v-on:submit.prevent="onSubmit">
            <b-form-group
                id="form-group-name"
                label="Form Name:"
                label-for="form-name"
                >
                <b-form-input
                    id="form-name"
                    v-model="name"
                    type="text"
                    required
                    placeholder="Enter Form name"
                    ></b-form-input>
            </b-form-group>
            
            <input
                type="file"
                ref="files"
                multiple
                style="display:none"
                @change="uploadFieldChange"
            />
            <b-progress :value="percentCompleted" :max="100" show-progress animated v-if="percentCompleted"></b-progress>
            <b-list-group v-if="files.length">
                <b-list-group-item v-for="(file, index) in files" v-bind:key="index">
                    {{file.file.name}}
                    <b-button 
                        variant="primary" 
                        @click="uploadFile(index)" 
                        class="float-right" 
                        size="sm" 
                        v-if="!file.uploaded"
                        :disabled="uploading===index"
                        >Upload</b-button>
                </b-list-group-item>
            </b-list-group>
            
            <b-alert
                :show="showAlert"
                dismissible
                fade
                variant="danger"
                @dismiss-count-down="countDownChanged"
            >
                File too big, please select a file less than 2mb
            </b-alert>
            
            <b-button variant="success" @click="addFile">Add file</b-button>
            
            <b-button type="submit" variant="primary">Save Form</b-button>
        </b-form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: '',
                files: [],
                uploaded: [],
                percentCompleted: 0,
                uploading: false,
                maxsize: 2048,
                showAlert: 0
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            addFile() {
                this.$refs.files.click();
            },
            uploadFieldChange(event) {
                let files = event.target.files || event.dataTransfer.files;
                for (let i = 0; i < files.length; i++) {
                    const size = Math.round((files[i].size / 1024));
                    if (size < this.maxsize) {
                        this.files.push({
                            file: files[i],
                            uploaded: null
                        });    
                    } else {
                        this.showAlert = 3
                    }
                }
            },
            uploadFile(index){
                if(this.uploading === index){
                    return
                }
                this.uploading = index
                let data = new FormData()
                data.append('file', this.files[index].file)

                let config = {
                    headers : {
                        'Content-Type' : 'multipart/form-data'
                    },
                    onUploadProgress: progressEvent => {
                        this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                    }
                }

                axios.post('/api/file/upload', data, config).then(
                    response => {
                        this.uploading = false
                        this.percentCompleted = 0
                        this.files[index].uploaded = response.data.fileName
                        this.uploaded.push({
                            name: response.data.fileName,
                            originalName:this.files[index].file.name
                        })
                    }
                )
            },
            onSubmit() {
                axios.post('/api/form', {
                    name: this.name,
                    formFiles: this.uploaded
                }).then(
                    response => {
                        this.$bvModal.hide('modalAddForm')
                        this.$emit('form-added', response.data.data);
                    }
                )
            },
            countDownChanged(dismissCountDown) {
                this.showAlert = dismissCountDown
            },
        }
    }
</script>

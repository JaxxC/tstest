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
                    >
                </b-form-input>
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
                <b-list-group-item 
                    v-for="(file, index) in files" 
                    v-bind:key="index"
                    :variant="getListItemVariant(index)"
                    >
                    <div class="row">
                        <div class="col-md-10">
                            <b-form-input 
                                v-model="file.title" 
                                :id="'fileTitle'+index"
                                placeholder="Enter file title" 
                                :disabled="file.uploaded"
                                size="sm"
                                :state="getFileInputState(index)">
                            </b-form-input>
                        </div>
                        <div class="col-md-2">
                            <b-button 
                                variant="primary" 
                                @click="uploadFile(index)" 
                                class="float-right" 
                                size="sm" 
                                v-if="!file.uploaded"
                                :disabled="uploading===index || getFileInputState(index)===false"
                                >
                                Upload
                            </b-button>
                        </div>
                    </div>
                    <div class="row">
                        <b-form-invalid-feedback :id="'fileTitle'+index+'-feedback'">
                            You must fill title field
                        </b-form-invalid-feedback>
                    </div>
                </b-list-group-item>
            </b-list-group>
            
            <b-alert
                :show="showAlert"
                dismissible
                fade
                variant="danger"
                @dismiss-count-down="countDownChanged">
                {{alertMessage}}
            </b-alert>
            
            <b-button variant="success" @click="addFile">Add file</b-button>
            
            <b-button type="submit" variant="primary">Save Form</b-button>
        </b-form>
    </div>
</template>

<script>
    import formsRepository from '../repositories/forms'
    import { CREATE_FORM } from '../store/types/forms'
    import { DATA_ERROR } from '../store/types/data'
    import { mapGetters } from 'vuex'
    
    export default {
        data() {
            return {
                name: '',
                files: [],
                uploaded: [],
                percentCompleted: 0,
                uploading: false,
                maxsize: 2048,
                showAlert: 0,
                alertMessage: '',
                submitted: false
            }
        },
        computed: {
            ...mapGetters(['getErrorMessage']),
        },
        methods: {
            addFile() {
                this.$refs.files.click()
            },
            getListItemVariant(index){
                if(this.submitted){
                    return (this.files[index].uploaded) ? 'default' : 'danger'
                } else {
                    return 'default'
                }
            },
            getFileInputState(index){
                return (this.files[index].title.length > 0) ? null : false
            },
            uploadFieldChange(event) {
                let files = event.target.files || event.dataTransfer.files
                for (let i = 0; i < files.length; i++) {
                    const size = Math.round((files[i].size / 1024))
                    if (size < this.maxsize) {
                        this.files.push({
                            file: files[i],
                            uploaded: false,
                            title: files[i].name
                        }) 
                    } else {
                        this.alertMessage = 'File too big, please select a file less than 2mb'
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
                        this.percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total )
                    }
                }

                axios.post('/api/file/upload', data, config).then(
                    (response) => {
                        this.uploading = false
                        this.percentCompleted = 0
                        this.$set(this.files[index], 'uploaded', true)
                        this.uploaded.push({
                            name: response.data.fileName,
                            title: this.files[index].title,
                            originalName:this.files[index].file.name
                        })
                    }
                )
            },
            onSubmit() {
                if(this.hasNotUploadedFiles()){
                    this.submitted = true
                    this.alertMessage = 'Some of your files are not uploaded. Please upload them before save form'
                    this.showAlert = 3
                    return
                }
                
                this.$store.dispatch(CREATE_FORM, {
                    name: this.name,
                    formFiles: this.uploaded
                }).then(
                    (response) => {
                        this.$bvModal.hide('modalAddForm')
                    }).catch((error) => {
                        this.$store.commit(DATA_ERROR, error)
                        this.showError()
                    })
                
            },
            hasNotUploadedFiles(){
                return this.files.filter(item => !item.uploaded).length > 0
            },
            countDownChanged(dismissCountDown) {
                this.showAlert = dismissCountDown
            },
            showError(){
                this.alertMessage = this.getErrorMessage
                this.showAlert = 5
            }
        }
    }
</script>

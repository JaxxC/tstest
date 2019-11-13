<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <b-alert show variant="success">
                    <h4 class="alert-heading">Technical test - TeamScale</h4>
                    <p>
                        Currently max file upload size limited to 2Mb. It was not specified, so I leave php.ini settings without changes
                    </p>
                    <hr>
                    <p class="mb-0">
                        Used latest Laravel+Vue, and Bootstrap lib
                    </p>
                </b-alert>
                
                
                <b-button block variant="primary" v-b-modal.modalAddForm class="my-4">Add Form</b-button>
                
                <b-modal id="modalAddForm" title="Add Form" hide-footer >
                    <add-form-dialog v-on:form-added="formAdded"></add-form-dialog>
                </b-modal>
                
                <b-table
                    v-if="forms.length"
                    hover 
                    bordered 
                    :items="forms"
                    head-variant="dark"
                    :fields="fields"
                    >
                    <template v-slot:cell(index)="data">
                        {{ data.index + 1 }}
                    </template>
                    <template v-slot:cell(name)="data">
                        {{ data.item.name }}
                    </template>
                    <template v-slot:cell(view)="data">
                        <b-button size="sm" variant="primary" @click="viewForm(data.item.id)" class="mr-2">
                            View
                        </b-button>
                    </template>
                </b-table>
                <b-alert variant="warning" show v-else>No forms saved yet</b-alert>
                
                <b-modal id="modalViewForm" title="Form Files" ok-only>
                    <view-form-dialog :form="activeForm" v-if="activeForm"></view-form-dialog>
                </b-modal>
            </div>
        </div>
    </div>
</template>

<script>
    import AddFormDialog from './AddFormDialog.vue'
    import ViewFormDialog from './ViewFormDialog.vue'
    
    export default {
        data() {
            return {
                forms: [],
                fields: [{ key: 'index', label: '#' },{ key: 'name', label: 'Form Name' }, { key: 'view', label: 'View' }],
                activeForm: null
            }
        },
        created() {
            axios.get('/api/forms').then(
                response => {
                    this.forms = response.data.data
                }
            )
        },
        methods: {
            viewForm(index){
                axios.get(`/api/form/${index}`).then(
                    response => {
                        this.activeForm = response.data.data
                        this.$bvModal.show('modalViewForm')
                    }
                )
            },
            formAdded(form){
                console.log(form)
                this.forms.push(form)
            }
        },
        components: {
            'add-form-dialog': AddFormDialog,
            'view-form-dialog': ViewFormDialog
        }
    }
</script>

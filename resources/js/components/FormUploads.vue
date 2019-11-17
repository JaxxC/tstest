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
                        Used latest Laravel+Vue, and Bootstrap lib.
                        <a href='https://github.com/JaxxC/tstest/tree/master' target="_blank">Source code</a> <br>
                        <a href='https://github.com/JaxxC/tstest/tree/vuex' target="_blank">Source code with Vuex usage</a>
                    </p>
                </b-alert>
                
                
                <b-button block variant="primary" v-b-modal.modalAddForm class="my-4">Add Form</b-button>
                
                <b-modal id="modalAddForm" title="Add Form" hide-footer >
                    <add-form-dialog></add-form-dialog>
                </b-modal>
                
                <b-table
                    v-if="formsLoaded"
                    hover 
                    bordered 
                    :items="getForms"
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
                    <view-form-dialog :form="getActiveForm" v-if="activeFormLoaded"></view-form-dialog>
                </b-modal>
            </div>
        </div>
    </div>
</template>

<script>
    import AddFormDialog from './AddFormDialog.vue'
    import ViewFormDialog from './ViewFormDialog.vue'
    import formsRepository from '../repositories/forms'
    import { GET_FORMS, GET_FORM } from '../store/types/forms'
    import { mapGetters } from 'vuex'
    
    export default {
        data() {
            return {
                fields: [
                    { key: 'index', label: '#' },
                    { key: 'name', label: 'Form Name' }, 
                    { key: 'view', label: 'View' }
                ],
                activeForm: null
            }
        },
        computed: {
            ...mapGetters(['getForms', 'formsLoaded', 'getActiveForm', 'activeFormLoaded']),
        },
        created() {
            this.$store.dispatch(GET_FORMS);
        },
        methods: {
            viewForm(id){
                this.$store.dispatch(GET_FORM, id);
                this.$bvModal.show('modalViewForm')
            }
        },
        components: {
            'add-form-dialog': AddFormDialog,
            'view-form-dialog': ViewFormDialog
        }
    }
</script>

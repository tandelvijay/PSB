<template>
    <div class="container">
        <div>
            <div class="float-left">
                <a href="#" class="btn btn-primary" role="button">Back to Client</a>
            </div>
            <div class="float-lg-right">
                <input type="text" placeholder="Search" v-model="search">
            </div>
            <div class="clearfix"></div>

            <div class="client-list mt-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="float-left">
                                    <h2 class="butt" style="font-size: 19px; color: #4567a4;">Client Name</h2>
                                </div>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" title="Change the name or description of this Client">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm" title="Delete this Clients and all of its presentations">Delete</button>
                                </div>
                                <div class="clearfix"></div>
                                <hr>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="client-list mt-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="text-body">
                                <a href="" class="mr-2">EDIT</a>
                                <a href="" class="mr-2">COPY</a>
                                <a href="" class="mr-2">MOVE</a>
                                <a href="" class="mr-2">SHARE</a>
                                <a href="" class="mr-2">DELETE</a>
                            </div>

                            <div class="clearfix"></div>
                            <hr>
                            <div class="text-body">
                                <span>Here Message display</span>
                            </div>
                                <hr>
                            <div class="text-body">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <notifications animation-type="velocity"/>
        <loading v-show="showLoader"></loading>
    </div>
    </div>
</template>

<script>
    import notify from './shared/Notify'
    import common from './shared/Common';

    require('../main');
    export default {
        name: "PresentationComponent",
        data: function(){
            return {
                showLoader: false,
                url: 'create-folder',
                formData: {
                    id: 0,
                    title: '',
                    description: ''
                },
                presentationFormData: {
                    id: 0,
                    title: '',
                    description: '',
                    client_id: 0,
                    is_sales_presentation: 1
                },
                clientList: [],
                lists:[],
                search:'',
                clientName: ''
            }
        },
        created: function () {
            this.showLoader = true;
            this.getClientList();
        },

        methods: {
            // get the list of all clients
            getClientList: function () {
                this.showLoader = true;
                const url = common.data().serverPath + 'getClients';

                Axios.get(url).then((response) => {
                    this.showLoader = false;
                if (response.data.success) {
                    this.clientList = response.data.data.records;
                } else {
                    notify.methods.notifyError(response.data.error.message);
                }

            }).catch((error) => {
                    this.showLoader = false;
                notify.methods.notifyError('Something went wrong. Please refresh the page.');
            })
            },

            // Delete client
            deleteClient: function (id) { // save annual rate
                if (confirm("Are you sure you want to delete this Client?")) {
                    this.showLoader = true;
                    const url = common.data().serverPath + 'delete-Client/' + id;
                    Axios.delete(url)
                            .then((response) => {
                        this.showLoader = false;
                    if (response.data.success) {
                        notify.methods.notifySuccess(response.data.message);
                        this.getClientList();

                    } else {
                        notify.methods.notifyError(response.data.error.message);
                    }
                })
                .catch((error) => {
                        this.showLoader = false;
                    notify.methods.notifyError('Something went wrong. Please try again.');
                })
                }
            },


            // edit client
            editClient(client){
                this.formData = {
                    id: client.id,
                    title: client.title,
                    description: client.description
                };
                $('#modelClient').modal('show');
            },

            // create/update new client
            createClient: function () {
                this.showLoader = true;
                const url = common.data().serverPath + 'create-client';

                Axios.post(url, this.formData).then((response) => {
                    this.showLoader = false;
                if (response.data.success) {
                    notify.methods.notifySuccess(response.data.message);
                    $('#modelClient').modal('hide');
                    this.getClientList();
                } else {
                    if (response.data.error.statusCode === 103) {
                        notify.methods.notifyError(response.data.error.errorDescription);
                    } else {
                        notify.methods.notifyError(response.data.error.message);
                    }
                }

            }).catch((error) => {
                    this.showLoader = false;
                notify.methods.notifyError('Something went wrong. Please try again.');
            })
            },


            // create/update new presentation
            createPresentation: function () {
                this.showLoader = true;
                const url = common.data().serverPath + 'create-presentation';

                Axios.post(url, this.presentationFormData).then((response) => {
                    this.showLoader = false;
                if (response.data.success) {
                    notify.methods.notifySuccess(response.data.message);
                    $('#modelPresentation').modal('hide');
                    this.getClientList();
                } else {
                    if (response.data.error.statusCode === 103) {
                        notify.methods.notifyError(response.data.error.errorDescription);
                    } else {
                        notify.methods.notifyError(response.data.error.message);
                    }
                }

            }).catch((error) => {
                    this.showLoader = false;
                notify.methods.notifyError('Something went wrong. Please try again.');
            })
            },

            /**
             * Open create folder pop-up
             */
            openCreateClientModal: function() {
                this.formData = {
                    id: 0,
                    title: '',
                    description: ''
                };
                $('#modelClient').modal('show');
            },
            /**
             * open create presentation pop-up
             */
            openCreatePresentationModal: function(client) {
                this.presentationFormData = {
                    id: 0,
                    title: '',
                    description: '',
                    client_id: client.id,
                    is_sales_presentation: 1
                };
                this.clientName = client.title;
                $('#modelPresentation').modal('show');
            }
        },
        computed: {
            filteredItems() {
                return this.clientList.filter(client => {
                            return client.title.indexOf(this.search.toLowerCase()) > -1
                        })
            }
        }
    }
</script>
<template>
    <div class="container">
        <div>
            <div class="float-left">
                <a href="#" class="btn btn-primary" role="button">Visit the Vault</a>
                <a href="#" class="btn btn-success" @click="openCreateClientModal">New Client</a>
            </div>
            <div class="float-lg-right">
                <input type="text" placeholder="Search" v-model="search">
            </div>
            <div class="clearfix"></div>

            <div class="client-list mt-3">
                <div class="row">
                    <div class="col-md-4" v-for="client in filteredItems">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="butt" style=" padding-top: 7px; padding-bottom: 7px; margin-bottom: 7px;border-bottom: solid 2px #fff;font-size: 19px;font-family: 'AvenirLT-Medium';color: #4567a4;padding-right: 130px;text-shadow: 0px 2px 0px white;">{{ client.title }}</h2>
                                <span class="links" style="position: absolute; top: 27px;right: 15px;color: #aec1cb;text-shadow: 0px 1px 0px white;font-family: 'AvenirLT-Medium';text-transform: uppercase;font-size: 1em;">
                                      <a href="#" data-action="editClient" title="Change the name or description of this Client" @click="editClient(client)">Edit</a>
                                      <a href="#" data-action="deleteClient" title="Delete this Clients and all of its presentations" @click="deleteClient(client.id)">Delete</a>
                                 </span>
                                <hr>
                                <em>{{ client.description }}</em>
                                <div class="buttons" style="width: 210px    ;margin: 0 auto;">
                                    <a href="#" class="btn btn-round" style="background-color: #fc6300;border-radius: 30px;"  data-action="newPresentation" ><b>New Presentation</b></a><br>
                                    <a href="#" class="btn btn-primary" style="border-radius: 30px;" data-action="showPresentations">
                                        <b>Show Presentations</b>
                                    </a>
                                </div>
                                <small class="left" style="float: left;padding-left: 15px;">
                                    Created  <strong>{{ client.created_at }}</strong>
                                </small>
                                <small class="middle">
                                </small>
                                <small class="right">
                                    Updated  <strong>{{ client.updated_at }}</strong>
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="modelClient">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form v-on:submit.prevent="createClient" id="clientForm" class="form-horizontal">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Client</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="formGroupExampleInput">Title</label>
                                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Enter Title" v-model="formData.title">
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Description</label>
                                    <input type="text" class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Description" v-model="formData.description">
                                </div>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
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
        name: "HomeComponent",
        data: function(){
            return {
                showLoader: false,
                url: 'create-folder',
                formData: {
                    id: 0,
                    title: '',
                    description: ''
                },
                clientList: [],
                lists:[],
                search:''
            }
        },
        created: function () {
            this.showLoader = true;
            this.getClientList();
        },
        /*watch:{
         searchQuery(){

         if( this.searchQuery.length>0) {
         this.clientList = this.lists.filter((client) => {
         return Object.keys(client).some((client) => {
         let test123 = String(client[key]);
         return string.toLowerCase().indexOf(this.searchQuery().toLowerCase()) > -1
         })
         })
         } else {
         this.client=this.lists

         }

         }

         }
         },*/
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
                console.log()
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
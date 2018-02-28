@extends('layouts.app')

@section('content')

  <div class="container">
    <h3>Factory List</h3>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
      Add New Factory
    </button>
    <div class="" id="app">
      <span class="pull-right" v-if="loading">
        <i class="fa fa-refresh fa-spin fa-1x fs-fw"></i>
      </span>
      <br>
      <div class="row">
        <div class="col-md-4 col-sm-6 mr-auto">
          <input type="text" name="search" placeholder="Search" v-model="searchQuery" class="form-control" value="">
        </div>
      </div>
      <br>
      <ul class="list-group">
        <li v-for="factory, key in temp" class="list-group-item">
          @{{ factory.name }}
          <div class="pull-right">
            <button type="button" @click="disable_enable(key, factory)" class="btn btn-sm btn-success ml-auto" v-if="factory.isActive == 1"  name="button">Disable</button>
            <button type="button" @click="disable_enable(key, factory)" class="btn btn-sm ml-auto" v-else name="button">Enable</button>
            <a class="btn btn-primary btn-sm mr-auto" name="button" href="" >View Reports</a>
            <button type="button" class="btn btn-info btn-sm" name="button">Edit Factory</button>
            <button type="button" class="btn btn-danger btn-sm" @click="del(key, factory.id)" name="button">Delete Factory</button>
          </div>
        </li>
      </ul>
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Factory</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="name">Factory Name</label>
                <input type="text" v-model="newfactory.name" name="name" value="" placeholder="Factory Name" class="form-control">
              </div>
              <div class="form-group">
                <label for="name">Active</label>
                <select class="form-control" name="isActive" v-model="newfactory.isActive">
                  <option value="1">Enable</option>
                  <option value="0">Disable</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" @click="addFactory" data-dismiss="modal">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection


@section('scripts')
  <script src="{{ asset('./js/vue.js') }}" charset="utf-8"></script>
  <script src="{{ asset('./js/axios.js') }}" charset="utf-8"></script>
  <script type="text/javascript">
  var app = new Vue({
    el: '#app',
    data() {
      return{
        factories:{},
        temp:{},
        loading: false,
        errors:{},
        newfactory:{
          name:'',
          isActive:''
        },
        searchQuery:''
      }
    },
    watch:{
      searchQuery(){
        if(this.searchQuery.length > 0){
          this.temp = this.factories.filter((factory) => {
            return Object.keys(factory).some((key) => {
              let string = String(factory[key])
              return string.toLowerCase().indexOf(this.searchQuery.toLowerCase()) > -1
            })
          });
        } else {
          this.temp = this.factories
        }
      }
    },
    created(){
      // fetch all the factories from the database
      this.fetchFactory()
    },
    methods:{
      disable_enable(key, id){
        this.loading = !this.loading
        var factory = this.factories[key]
        console.log(factory)
        if(this.factories[key].isActive == 1){
          if(confirm("Are you sure, You want to revoke all the rights from the factory")){
            factory.isActive = 0
            axios.get(`/admin/factory/endis/${factory.id}`)
            .then((response) => console.log(response))
            .catch((error) => this.errors = error.response.data.errors)
          }
        }
        else{
          if(confirm("Are you sure, You want to Activate this factory?")){
            factory.isActive = 1
            axios.get(`/admin/factory/endis/${factory.id}`)
            .then((response) => console.log(response))
            .catch((error) => this.errors = error.response.data.errors)
          }
        }
        this.fetchFactory();
        this.loading = !this.loading
      },
      del(key, id){
        this.loading = !this.loading
        if(confirm("Are you sure to delete this factory?")){
          axios.delete(`/admin/factory/${id}`)
          .then((response) => {
            this.factories.splice(key, 1);
          }).catch((error) => {
            this.errors = error.response.data.errors
          })
        }
        this.loading = !this.loading
      },
      fetchFactory(){
        // fetch factory
        axios.post('/admin/factories').then((response) => this.factories = this.temp = response.data)
        .catch((error) => this.errors = error.response.data.errors)
      },
      addFactory(){
        axios.post('/admin/factory', this.$data.newfactory).then((response) => {
          this.newfactory.name = '';
          this.newfactory.isActive = '';
          this.factories.push(response.data);
          this.factories.sort(function(a, b){
            if(a.name > b.name){
              return 1;
            } else {
              return -1;
            }
          })
        }).catch((error) => this.errors = error.response.data.errors)
      }
    }
  });
  </script>
@endsection

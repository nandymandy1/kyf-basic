@extends('layouts.app')

@section('content')
<div class="container">
  <h3>All Factory Owners</h3>
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
    
  </div>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('./js/vue.js') }}" charset="utf-8"></script>
  <script src="{{ asset('./js/axios.js') }}" charset="utf-8"></script>
  <script type="text/javascript">
  var app = new Vue({
    el: '#app'
    data(){
      return{

      }
    },
    methods:{

    },
    created(){

    },
    watch{
        // Search Query Goes here
    }
  });
@endsection

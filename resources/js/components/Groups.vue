<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <create-group :AllUsers="users"></create-group>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <div class="list-group">
                    <a :href="'/group/'+group.id" class="list-group-item list-group-item-action" v-for="group in groups">{{group.name}}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                groups: [],
                users:[],
                loggedUser:null
            }
        },
        mounted() {
            this.fetchUsersAndGroups()
            //this.groups = this.initialGroups;
            /*Bus.$on('groupCreated', (group) => {
                this.groups.push(group);
            });*/
        },
        methods: {
            async fetchUsersAndGroups(){
                await axios.get('api/groups').then((response) =>{
                    console.log(response)
                    this.groups = response.data.LoggedUserGroups
                    this.users = response.data.AllUsers
                    this.loggedUser = response.data.LoggedUser
                    this.listenForNewGroups();
                })
            },
            listenForNewGroups(){
                Echo.private('users.' + this.loggedUser.id)
                    .listen('.GroupCreated', (e) => {
                        console.log(e)
                        this.groups.push(e.group);
                    });
            },
        }
    }
</script>

<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{group.name}}</div>
                    <div class="card-body">
                        <div v-for="conversation in conversations">
                            <hr>
                            <h6><strong>{{conversation.user.name}}</strong></h6>
                            <p class="pl-4">{{conversation.message}}</p>
                        </div>
                        <form @submit.prevent="store">
                            <input id="btn-input" required type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="message">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        utilisateurs connectés
                    </div>
                    <div class="card-body">
                        <p v-for="user in usersInGroup" :key="user.id">
                            {{user.name}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['group'],
        data() {
            return {
                conversations: [],
                message: '',
                group_id: this.group.id,
                usersInGroup: null
            }
        },
        mounted() {
            document.querySelector('.card-body').scrollTop = document.querySelector('.card-body').scrollHeight;
            this.fetchConversationGroup();
            this.listenForNewMessage();
        },
        methods: {
            store() {
                axios.post(`api/group/${this.group_id}/conversation`, {message: this.message, group_id: this.group.id})
                    .then((response) => {
                        this.message = '';
                        document.querySelector('.card-body').scrollTop = document.querySelector('.card-body').scrollHeight;
                        //this.conversations.push(response.data);
                    });
            },
            async fetchConversationGroup(){
                await axios.get(`api/group/${this.group_id}/conversation`).then((response)=>{
                    this.conversations = response.data
                })
                document.querySelector('.card-body').scrollTop = document.querySelector('.card-body').scrollHeight;
            },
            listenForNewMessage() {
                Echo.join('groups.' + this.group_id)
                    .here((users) => {
                        console.log(users)
                        this.usersInGroup = users
                    })
                    .joining((user)=>{
                        console.log('-----join-----')
                        console.log(user)
                        //this.usersInGroup = this.usersInGroup.push(user)
                        this.usersInGroup.push(user)

                    })
                    .leaving((userL)=>{
                        console.log('-----leave-----')
                        console.log(userL)
                        this.usersInGroup = this.usersInGroup.filter(el => el.id !== userL.id)
                    })
                    .listen('.NewMessage', (e) => {
                        // console.log(e);
                        this.conversations.push(e);
                        document.querySelector('.card-body').scrollTop = document.querySelector('.card-body').scrollHeight;
                    });
            }
        }
    }
</script>

<style scoped>
    .card-body{
        max-height:50vh;
        overflow:auto;
    }
</style>

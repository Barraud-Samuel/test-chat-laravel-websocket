<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <div class="card-body">
                        <div v-for="conversation in conversations">
                            <hr>
                            <h6><strong>{{conversation.user}}</strong></h6>
                            <p class="pl-4">{{conversation.message}}</p>
                        </div>
                        <form @submit.prevent="store">
                            <input id="btn-input" required type="text" name="message" class="form-control input-sm" placeholder="Type your message here..." v-model="message">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                conversations: [],
                message: '',
            }
        },
        mounted() {
            console.log('Component mounted.')
            this.getConversations();
            this.listen();
            this.scrollToElement();
        },
        methods: {
            store() {
                axios.post('/api/conversations/store', {
                    message: this.message
                })
                    .then((response) => {
                        this.message = '';
                        console.log(response)
                        this.conversations.push(response.data);
                    });
            },
            getConversations() {
                axios.get('/api/conversations', {})
                    .then((response) => {
                        this.conversations = response.data;
                    });
            },
            listen() {
                Echo.channel('public-chat')
                    .listen('NewMessage', (e) => {
                        this.conversations.push(e.conversation);
                    });
            },
            scrollToElement() {
                    const container = document.querySelector('.container');
                    const { scrollHeight } = container;
                    container.scrollTop = scrollHeight;
            },
        }
    }
</script>
<style>
    p{
        margin: 0;
    }
    hr{
        margin-top: 5px;
        margin-bottom: 5px;
    }
    h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
        margin-bottom: 0;
        font-weight: 500;
        line-height: 1.2;
    }

    .container{
        overflow:auto;
        max-height: 70vh;
    }
</style>

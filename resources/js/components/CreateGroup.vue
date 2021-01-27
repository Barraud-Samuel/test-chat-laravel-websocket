<template>
    <div class="panel panel-default">
        <div class="panel-heading">Create Group</div>
        <div class="panel-body">
            <form>
                <div class="form-group">
                    <input class="form-control" type="text" v-model="name" placeholder="Group Name">
                </div>
                <div class="form-group">
                    <select v-model="users" multiple id="friends">
                        <option v-for="user in AllUsers" :value="user.id">
                            {{ user.name }}
                        </option>
                    </select>
                </div>
            </form>
        </div>
        <div class="panel-footer text-center">
            <button type="submit" @click.prevent="createGroup" class="btn btn-primary">Create Group</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['AllUsers'],
        data() {
            return {
                name: '',
                users:[]
            }
        },
        methods: {
            async createGroup() {
                await axios.post('/api/group', {name: this.name, users: this.users})
                    .then((response) => {
                        this.name = '';
                        this.users = [];
                        // Bus.$emit('groupCreated', response.data);
                    });
            },
        }
    }
</script>

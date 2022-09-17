<template>
    <div>
        名前:<input type="text" name="name" id="name" v-model="myname">
        メッセージ:<input
            type="text"
            name="message"
            id="message"
            v-model="mymessage">
        <button @click="send">送信</button>

        <ul id="messages">
            <li v-for="(message, index) in messages" :key="index">{{ message.myname }}: {{ message.mymessage }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                myname: '',
                mymessage: '',
                messages: [],
            }
        },

        methods: {
            send() {
                axios.post('/messages', {
                    name: this.myname,
                    message: this.mymessage,
                })
                .then(response => {
                    this.messages.push({
                        myname: response.data.name,
                        mymessage: response.data.message,
                    });

                    this.mymessage = '';
                })
                .catch(function (error) {
                    console.log(error);
                });
            }
        },

        mounted() {
            window.Echo.channel('chat-channel')
                .listen('MessageSent', (response) => {
                    this.messages.push({
                        myname: response.inputs.name,
                        mymessage: response.inputs.message,
                    });
            });
        }
    }
</script>

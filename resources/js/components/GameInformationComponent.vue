<template>
    <dl>
        <dt>ターン</dt>
        <dd>{{ current_turn }}</dd>
    </dl>
</template>

<script>
    export default {
        props: {
            game: Object,
            player: Object,
        },

        data() {
            return {
                current_turn: 1,
            }
        },

        created() {
            this.current_turn = this.game.turn.number;
        },

        mounted() {
            window.Echo.channel('player-channel')
                .listen('GameInformationChanged', (response) => {
                    this.current_turn = response.turn.number;
                });
        }
    }
</script>

<template>
    <div>
        <form v-if="isMyTurn">
            <div class="form-group row" v-for="(fleet, index) in player.fleets" :key="fleet.id">
                <label :for="'tf' + fleet.number" class="col-sm-2 col-form-label">TF{{ fleet.number }}</label>
                <div class="col-sm-10">
                    <input v-model="hexNumbers[index]" type="number" name="position[hex_numbers][]" class="form-control" :id="'tf' + fleet.number" placeholder="ヘクス番号を入力">
                </div>
            </div>
            <button type="submit" class="btn btn-primary" @click.prevent="send">決定</button>
        </form>

        <p v-else>{{ message }}</p>

        <div v-if="turn.status === 3">
            <ul>
                <li v-for="(value, index) in ret" :key="index">{{ value }}で戦闘が起こりました。</li>
            </ul>
            <button class="btn btn-warning" @click="nextTurn">戦闘終了</button>
        </div>

    </div>
</template>

<script>
    export default {
        props: {
            game: Object,
            player: Object,
        },

        data() {
            return {
                isMyTurn: false,
                hexNumbers: [],
                message: '相手の入力が終わるまでしばらくお待ちください…',
                ret: [],
                turn: '',
            }
        },

        created() {
            this.turn = this.game.turn;
            if (this.game.turn.current_player_id === this.player.id) {
                this.isMyTurn = true;
            }
        },

        methods: {
            send() {
                axios.post('/positions', {
                    fleet_ids: _.map(this.player.fleets, 'id'),
                    hex_numbers: this.hexNumbers,
                    player_id: this.player.id,
                    game_id: this.game.id,
                    turn_id: this.game.turn.id,
                })
                .then(response => {
                    this.hexNumbers = [];
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            nextTurn() {
                axios.post('/turns/' + this.turn.id, {
                    '_method': 'PATCH',
                });
            },
        },

        mounted() {
            window.Echo.channel('player-channel')
                .listen('PositionsSent', (response) => {
                    if (response.turn.status !== 3) {
                        if (response.turn.current_player_id === this.player.id) {
                            this.isMyTurn = true;
                        } else {
                            this.isMyTurn = false;
                        }
                    } else {
                        this.isMyTurn = false;
                    }
                    this.turn = response.turn;
                })
                .listen('ZocResponse', (response) => {
                    this.message = '判定結果';

                    this.ret = response.ret;
                })
                .listen('TurnFinished', (response) => {
                    if (response.turn.current_player_id === this.player.id) {
                        this.isMyTurn = true;
                    } else {
                        this.isMyTurn = false;
                    }
                    this.turn = response.turn;
                    this.message = '相手の入力が終わるまでしばらくお待ちください…';
                    this.ret = [];
                });
        }
    }
</script>

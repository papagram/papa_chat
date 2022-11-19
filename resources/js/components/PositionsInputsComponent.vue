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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>艦隊</th>
                        <th>自軍位置</th>
                        <th>戦闘発生位置</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(params, hexNumber) in myBattleInformation" :key="hexNumber">
                        <td>TF{{ params.fleet_number }}</td>
                        <td>{{ hexNumber }}</td>
                        <td>
                            <ul class="list-unstyled">
                                <li v-for="(battleHexNumber, index) in params.battle_hex_numbers" :key="index">{{ battleHexNumber }}</li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-warning" @click="nextTurn">戦闘終了</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            game: Object,
            player: Object,
            battleInformation: Object,
        },

        data() {
            return {
                isMyTurn: false,
                hexNumbers: [],
                message: '相手の入力が終わるまでしばらくお待ちください…',
                turn: '',
                myBattleInformation: [],
            }
        },

        created() {
            this.turn = this.game.turn;

            if (this.turn.status === 3) {
                this.isMyTurn = false;
                this.message = '判定結果';
                this.myBattleInformation = _.get(this.battleInformation, this.player.id);
            } else {
                if (this.turn.current_player_id === this.player.id) {
                    this.isMyTurn = true;
                }
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

                    this.myBattleInformation = _.get(response.battleInformation, this.player.id);
                })
                .listen('TurnFinished', (response) => {
                    if (response.turn.current_player_id === this.player.id) {
                        this.isMyTurn = true;
                    } else {
                        this.isMyTurn = false;
                    }
                    this.turn = response.turn;
                    this.message = '相手の入力が終わるまでしばらくお待ちください…';
                    this.myBattleInformation = [];
                });
        }
    }
</script>

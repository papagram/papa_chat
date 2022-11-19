<template>
    <div>
        <form v-if="isMyTurn">
            <div class="form-group row" v-for="(fleet, index) in player.fleets" :key="fleet.id" :class="{'has-error': hasError(index)}">
                <label :for="'tf' + fleet.number" class="col-sm-2 col-form-label">TF{{ fleet.number }}</label>
                <div class="col-sm-10">
                    <input v-model="hexNumbers[index]" @change="validate(index)" type="number" name="position[hex_numbers][]" class="form-control" :id="'tf' + fleet.number" placeholder="ヘクス番号を入力">
                    <b><span v-if="hasError(index)" style="color: red;">そこには移動できません</span></b>
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
            otherPlayer: Object,
            battleInformation: [Object, Array],
        },

        data() {
            return {
                isMyTurn: false,
                hexNumbers: [],
                message: '相手の入力が終わるまでしばらくお待ちください…',
                turn: '',
                myBattleInformation: [],
                currentPositions: [],
                errors: [],
            }
        },

        created() {
            this.turn = this.game.turn;

            let myCurrentPositions = [];
            _.each(this.player.fleets, (fleet) => {
                _.each(fleet.positions, (position) => {
                    myCurrentPositions.push(position.hex_number);
                });
            });

            let otherCurrentPositions = [];
            _.each(this.otherPlayer.fleets, (fleet) => {
                _.each(fleet.positions, (position) => {
                    otherCurrentPositions.push(position.hex_number);
                });
            });

            this.currentPositions[this.player.id] = myCurrentPositions;
            this.currentPositions[this.otherPlayer.id] = otherCurrentPositions;

            if (this.turn.status === 3) {
                this.isMyTurn = false;
                this.message = '判定結果';
                this.myBattleInformation = _.get(this.battleInformation, this.player.id);
            } else {
                if (this.turn.current_player_id === this.player.id) {
                    this.isMyTurn = true;
                }
            }

            for (let i = 0; i < this.player.fleets.length; i++) {
                this.errors[i] = false;
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

            validate(index) {
                const ret = _.includes(this.currentPositions[this.otherPlayer.id], parseInt(this.hexNumbers[index]));
                if (ret) {
                    this.errors.splice(index, 1, true);
                } else {
                    this.errors.splice(index, 1, false);
                }
            },

            hasError(index) {
                return this.errors[index];
            },
        },

        mounted() {
            window.Echo.channel('player-channel')
                .listen('PositionsSent', (response) => {
                    let positions = [];
                    if (parseInt(response.playerId) === this.player.id) {
                        _.each(response.positions, (position) => {
                            positions.push(position.hex_number);
                        });
                        this.currentPositions[this.player.id] = positions;
                    } else {
                        _.each(response.positions, (position) => {
                            positions.push(position.hex_number);
                        });
                        console.log(positions);
                        this.currentPositions[this.otherPlayer.id] = positions;
                    }

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

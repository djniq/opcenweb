import { SquadState } from '@/models/SquadModel';

export const getters = {
    getSquads(state: SquadState) {
        return state.squads;
    }
}
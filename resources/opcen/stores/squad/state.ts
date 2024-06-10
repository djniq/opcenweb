import { SquadState } from "@/models/SquadModel";

export const state = () :SquadState => {
    return {
        squads: [],
        squadOptions: [],
        squadCrewMembers: {},
        unassignedEmts: {},
        loadingSquads: false,
        loadingSquadOptions: false,
        loadingSquadMembers: false,
        processingSquad: false,
        processingSquadMember: false
    }
}
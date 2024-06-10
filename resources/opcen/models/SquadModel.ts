import { User } from "./UserModel";

export interface SquadState {
    squads: Squad[];
    squadOptions: Squad[];
    squadCrewMembers: SquadCrewMembers;
    unassignedEmts: UnassignedEmts;
    loadingSquads: boolean;
    loadingSquadOptions: boolean;
    loadingSquadMembers: boolean;
    processingSquad: boolean;
    processingSquadMember: boolean;
}

export interface Squad {
    id?: number;
    healthFacilityId?: number;
    healthFacilityName?: string;
    squadName?: string,
    status?: number;
    statusLabel?: string;
}

export interface SquadCrewMembers {
    [key: string]: User[];
}

export interface UnassignedEmts {
    [key: string]: User[];
}

import axios from 'axios';
import MockAdapter from "axios-mock-adapter";
import { faker } from '@faker-js/faker';
import { format } from 'date-fns';

// Create Mock for Patient EHR records 
const mock = new MockAdapter(axios);
mock.onGet(/\/ehr\/\d+/).reply( (config) => {
    return [200, {
        data: {
            patientFirstName: faker.person.firstName(),
            patientLastName: faker.person.lastName(),
            patientMiddleName: faker.person.middleName(),
            patientAddress: faker.location.street(),
            patientBirthdate: format(faker.date.birthdate(), 'yyyy-MM-dd'),
            patientBirthdateIsFortuitous: faker.datatype.boolean({ probability: 0.4 }), 
            chiefComplaint: "Sample chief complaint",
            remarks: "Sample remark from EHR" 
        },
      }]
    }
)
.onAny().passThrough();

const apiClient = axios.create({
    baseURL: '',
    withCredentials: true,
});

export default {
    // Patient EHR record api mocking
    getEhrData(id) {
        return apiClient.get(`/ehr/${id}`);
    },
};
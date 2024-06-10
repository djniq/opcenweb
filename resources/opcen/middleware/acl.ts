// Import router if you are using the middleware on Vue Router
import router from "@/router"; 

// Import store if you are using reactive Store/Pinia/Vuex as User data source
import { setActivePinia } from 'pinia';
import pinia from './pinia';
import { useUserStore } from "@/stores/user/userStore";
setActivePinia(pinia);
const store = useUserStore();

// ----- VUE 3 Imports -----
import { computed } from 'vue'; // For VUE 3
import { createAcl, defineAclRules } from 'vue-simple-acl';

// ---------------
// The Vue Simple ACL option 'user' can be a user OBJECT, FUNCTION returning a user object
// or an Asynchronous function returning a PROMISE of user object, suitable for performing fetch from API.

// User object from a {FUNCTION} or computed property like from Pinia/Vuex Store
// Suitable if you already has an existing logics authenticating and saving user data to Pinia/Vuex Store
const user = computed(() => store.user || {role: 'public'});

const rules = () => defineAclRules((setRule) => {
  // setRule('unique-ability', callbackFunction(user, arg1, arg2, ...) { });
  // setRule(['unique-ability-1', 'unique-ability-2'], callbackFunction(user, arg1, arg2, ...) { });

  // Define a simple rule for ability with no argument
  setRule('view-opcen', (user) => ['superadmin', 'hfadmin', 'opcen'].includes(user.role));
  setRule('view-admin', (user) => ['superadmin', 'hfadmin'].includes(user.role));
  setRule('act-as-admin', (user) => ['superadmin', 'hfadmin'].includes(user.role));
  setRule('create-request', (user) => ['superadmin', 'hfadmin', 'opcen'].includes(user.role));
  setRule('edit-request', (user) => ['superadmin', 'hfadmin', 'opcen'].includes(user.role));
  setRule('create-facility', (user) => user.role === 'superadmin');
  setRule('is-superadmin', (user) => user.role === 'superadmin');
  setRule('is-admin', (user) => user.role === 'hfadmin');
  setRule('is-opcen', (user) => user.role === 'opcen');
  setRule('is-emt', (user) => user.role === 'emt');
  
  // Define a simple rule for ability with an argument
  setRule([
    'edit-facility',
    'create-facility-user',
    'edit-facility-user',
    'create-facility-ambulance',
    'edit-facility-ambulance',
    'create-facility-driver',
    'edit-facility-driver'
  ], (user, facility) => (user.role === 'hfadmin' && user.facility.id === facility.id) || user.role === 'superadmin');

});

const simpleAcl = createAcl({
  user, // short for user: user
  rules, // short for rules: rules
  router, // OPTIONAL, short for router: router 
  // other optional vue-simple-acl options here... See Vue Simple ACL Options below
});

export default simpleAcl;
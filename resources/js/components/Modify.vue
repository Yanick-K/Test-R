<template>
  <div class="position-relative" v-if="!isHide">
    <div class="alert alert-success" role="alert" v-if="isChanged">
      Les informations ont bien été modifiés
    </div>

    <div class="d-flex">
      <button
        v-if="isAuth"
        class="btn btn-dark my-2 mx-2"
        @click="redirectToList()"
      >
        Retour à la liste
      </button>
      <button
        v-if="isSame"
        class="btn btn-primary my-2"
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="Récréer un nouveau formulaire à partir d'un nouvel utilisateur"
        @click="refreshSession()"
      >
        Rafraîchir la session
      </button>
    </div>
    <div class="alert alert-danger" role="alert" v-if="hasErrors">
      <h6>Une ou plusieurs erreurs sont survenurs veuillez réessayer</h6>
      <ul>
        <li v-for="(error, index) in errorsBack" :key="index">
          {{ error }}
        </li>
      </ul>
    </div>
    <div
      v-if="isLoading"
      style="z-index: 1000; opacity: 0.7"
      class="
        position-absolute
        top-0
        bottom-0
        start-0
        end-0
        align-items-center
        d-flex
        justify-content-center
        bg-light
      "
    >
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <div class="card p-3">
      <form @submit.prevent="submit()">
        <div class="row pb-2">
          <div class="col">
            <label for="inputEmail4" class="form-label">Prénom</label>
            <input
              type="text"
              class="form-control"
              aria-label="First name"
              name="first_name"
              v-model="form.prenom"
            />
          </div>
          <div class="col">
            <label for="inputEmail4" class="form-label">Nom</label>
            <input
              type="text"
              class="form-control"
              aria-label="Last name"
              name="last_name"
              v-model="form.nom"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 py-1">
            <label for="inputEmail4" class="form-label">Email</label>
            <ValidationProvider rules="email" v-slot="{ errors }">
              <input
                type="email"
                class="form-control"
                id="inputEmail4"
                v-model="form.email"
                name="email"
              />
              <span class="text-danger">{{ errors[0] }}</span>
            </ValidationProvider>
          </div>
          <div class="col-md-6 py-1">
            <label for="inputTel" class="form-label">Téléphone</label>
            <vue-tel-input
              v-model="form.telephone"
              @validate="validate($event)"
            ></vue-tel-input>
            <span class="text-danger" v-if="!isTelValid">
              Le téléphone n'est pas valide</span
            >
          </div>
          <div class="col-12 py-1">
            <label for="inputAddress" class="form-label">Adresse</label>
            <input
              type="text"
              class="form-control"
              id="inputAddress"
              name="adresse"
              v-model="form.adresse"
            />
          </div>

          <div class="col-md-3 py-1">
            <label for="inputZip" class="form-label">Code Postal</label>
            <input
              type="text"
              class="form-control disabled"
              id="inputZip"
              name="code_postal"
              v-model="form.code_postal"
            />
          </div>
          <div class="col-md-6 py-1">
            <label for="inputville" class="form-label">Ville</label>
            <input
              type="text"
              class="form-control"
              id="inputville"
              name="ville"
              v-model="form.ville"
              readonly
            />
          </div>
          <div class="col-12 py-1">
            <label for="exampleFormControlTextarea1" class="form-label"
              >Commentaire</label
            >
            <textarea
              class="form-control"
              id="exampleFormControlTextarea1"
              rows="3"
              name="Commentaire"
              v-model="form.commentaire"
            ></textarea>
          </div>
          <div class="col-12 d-flex justify-content-center pt-2">
            <button type="submit" class="btn btn-primary">Modifier</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  watch: {
    "form.code_postal": function (value) {
      if (value.length === 5) {
        axios
          .get(`https://geo.api.gouv.fr/communes?codePostal=${value}`)
          .then((resp) => (this.form.ville = resp.data[0].nom))
          .catch((error) => (this.form.ville = ""));
      }
    },
  },
  data() {
    return {
      form: {
        uuid: "",
        nom: "",
        prenom: "",
        ville: "",
        code_postal: "",
        commentaire: "",
        email: "",
        telephone: "",
        adresse: "",
      },
      isLoading: false,
      isTelValid: true,
      errorsBack: {},
      hasErrors: false,
      isChanged: false,
      isAuth: false,
      isHide: true,
      isSame: false,
    };
  },
  beforeCreate() {},
  created() {
    this.isAuth = window.isAuth;
    if (!window.isAuth && localStorage.getItem("uuid") !== window.contact.uuid)
      this.redirectToHome();
    else this.isHide = false;
    if (localStorage.getItem("uuid") === window.contact.uuid)
      this.isSame = true;
  },
  mounted() {
    if (window.contact) this.updateForm(window.contact);
    else this.refreshSession();
  },
  methods: {
    redirectToList() {
      const url = document.head.querySelector('meta[name="base-url"]').content;
      window.location.replace(`${url}/home`);
    },
    redirectToHome() {
      const url = document.head.querySelector('meta[name="base-url"]').content;
      window.location.replace(url);
    },
    updateForm(contact) {
      this.form.uuid = contact.uuid;
      this.form.nom = contact.last_name;
      this.form.prenom = contact.first_name;
      this.form.ville = contact.city;
      this.form.code_postal = contact.postal_code;
      this.form.commentaire = contact.comment;
      this.form.email = contact.email;
      this.form.telephone = contact.tel;
      this.form.adresse = contact.address;
    },
    validate(PhoneObject) {
      if (PhoneObject.valid !== undefined) this.isTelValid = PhoneObject.valid;
    },
    refreshSession() {
      const url = document.head.querySelector('meta[name="base-url"]').content;
      localStorage.removeItem("uuid");
      window.location.replace(url);
    },
    submit() {
      this.hasErrors = false;
      this.isLoading = true;
      axios
        .put(`/contacts/${window.contact.id}`, this.form)
        .then((resp) => {
          this.updateForm(resp.data);
          this.isLoading = false;
          this.isChanged = true;
          setTimeout(() => {
            this.isChanged = false;
          }, 3000);
        })
        .catch((error) => {
          this.hasErrors = true;
          if (error.response.data)
            this.errorsBack = Object.values(error.response.data.errors).flat();
          setTimeout(() => {
            this.hasErrors = false;
            this.errorsBack = {};
          }, 5000);
          this.isLoading = false;
        });
    },
  },
};
</script>

<style>
</style>
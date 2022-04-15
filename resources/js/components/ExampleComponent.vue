<template>
  <div class="container">
    <div class="row justify-content-center py-5">
      <h1 class="text-center">Contact</h1>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-8">
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
        <div
          style="z-index: 1000"
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
          v-if="isValidated"
        >
          <div class="alert alert-success" role="alert">
            Votre formulaire a bien été enregistré, vous serez redirigé pour le
            modifier d'ici : {{ seconde }} seconde(s)...
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
                  class="form-control"
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
                <button type="submit" class="btn btn-primary">Soumettre</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  watch: {
    "form.code_postal": function (value) {
      if (value.length === 5) {
        axios.get(`https://geo.api.gouv.fr/communes?codePostal=${value}`)
          .then((resp) => this.form.ville = resp.data[0].nom)
          .catch((error) => this.form.ville = '');
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
      isTelValid: true,
      errorsBack: {},
      hasErrors: false,
      isLoading: false,
      isValidated: false,
      seconde: 3,
      instanceAxios: {},
    };
  },
  created() {
    this.isLoading = true;
    const uuid = localStorage.getItem("uuid");
    if (uuid === null) this.generate_token();
    else this.check_token_valid(uuid);
  },
  methods: {
    redirectToModify(uuid) {
      const url = document.head.querySelector('meta[name="base-url"]').content;
      window.location.replace(`${url}/modify/${uuid}`);
    },
    refreshSession() {
      localStorage.removeItem("uuid");
      this.generate_token();
      this.isLoading = false;
    },
    check_token_valid(uuid) {
      axios
        .post("/token", { uuid: uuid })
        .then((resp) => {
          if (resp.data) {
            this.redirectToModify(uuid);
          } else this.refreshSession();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    generate_token() {
      axios
        .get("/uuid")
        .then((resp) => {
          this.form.uuid = resp.data;
          localStorage.setItem("uuid", resp.data);
          this.isLoading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    validate(PhoneObject) {
      if (PhoneObject.valid !== undefined) this.isTelValid = PhoneObject.valid;
    },
    submit() {
      this.hasErrors = false;
      this.isLoading = true;
      axios
        .post("/contacts", this.form)
        .then((resp) => {
          this.isLoading = false;
          this.isValidated = true;
          setInterval(() => {
            if (this.seconde > 0) this.seconde--;
          }, 1000);
          setTimeout(() => {
            this.redirectToModify(resp.data.uuid);
          }, 3000);
          //console.log("hello");
        })
        .catch((error) => {
          this.hasErrors = true;
          this.isLoading = false;
          if (error.response.data)
            this.errorsBack = Object.values(error.response.data.errors).flat();
          setTimeout(() => {
            this.hasErrors = false;
            this.errorsBack = {};
          }, 5000);
        });
    },
  },
};
</script>

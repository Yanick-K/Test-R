<template>
  <div>
    <div class="alert alert-success" role="alert" v-if="isDeleted">
      Le contact a bien été supprimé
    </div>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Prénom</th>
          <th scope="col">Nom</th>
          <th scope="col">Email</th>
          <th scope="col">Téléphone</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="lc in listContact" :key="lc.id">
          <td>{{ lc.id }}</td>
          <td>{{ lc.first_name }}</td>
          <td>{{ lc.last_name }}</td>
          <td>{{ lc.email }}</td>
          <td>{{ lc.tel }}</td>
          <td>
              <a :href="`${url}/modify/${lc.uuid}`">
            <button type="button" class="btn btn-primary btn-sm">
              Modifier
            </button>
              </a>
          </td>
          <td>
            <button
              type="button"
              class="btn btn-danger btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#modal"
              @click="instanceID = lc.id"
            >
              Supprimer
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="modal" tabindex="-1" id="modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation de suppression</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <p>Êtes vous sûr de vouloir supprimer l'élément correspondant à l'id <strong>{{instanceID}}</strong> ?</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Fermer
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="deleteContact()"
            >
              Confirmer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
  data() {
    return {
      instanceID: null,
      listContact: [],
      isDeleted: false,
      url : '',
    };
  },
  created() {
    this.getContacts();
    this.url = document.head.querySelector('meta[name="base-url"]').content
  },
  methods: {
    getContacts() {
      axios.get("contacts").then((resp) => {
        this.listContact = resp.data;
      });
    },
    deleteContact() {
      const myModal = Modal.getInstance(document.getElementById("modal"));
      axios
        .delete(`contacts/${this.instanceID}`)
        .then((resp) => {
          this.listContact = this.listContact.filter(
            (obj) => obj.id !== this.instanceID
          );
          this.isDeleted = true;
          this.instanceID = null;
          setTimeout(() => {
            this.isDeleted = false;
          }, 3000);
          myModal.hide();
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style>
</style>
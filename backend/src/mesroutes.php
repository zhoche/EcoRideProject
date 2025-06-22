
DATABASE = {
  users: [
    { id: 1, pseudo: 'Alice', password: 'password123', email: 'alice@gmail.com', role: 'user', credits: 20, rideIDs: ["id1", "id2"], vehiculeIDs: ["vehiculeID"], driverPreferences: {fumer: false, animaux: true} },
  ], 
  ride: [
    { id: 1, driverID: 1, departure: 'A', arrival: 'B', availableSeats: 5, price: 3, date: '2023-10-01T10:00:00Z', vehicle: 'vehiculeID', idPassengers: ["id1", "id2"] },
  ],
  vehicle: [
    { id: 1, ownerID: 1, brand: 'Toyota', model: 'Corolla', energy: 2020}
  ],
  avis: [
    { id: 1, rideID: 1, conducteurID: 1, passagerID: 1, rating: 5, comment: 'Great ride!', status: 'pending'}
  ]
}

// Pour se connecter
app.get('/login', (req, res) => {
})

// Pour cree un compte
app.get('/regsiter', (req, res) => {
})

// Pour crée un trajet
app.post('/ride/create', (req, res) => {
})

// Pour lister les trajets (qvec filtre )
app.get('/ride/list', (req, res) => {
})

// Pour s'enregistrer à un trajet
app.post('/ride/{ride_id}/register', (req, res) => {
})

// Pour s'enlever d'un trajet
app.post('/ride/{ride_id}/unregister', (req, res) => {
})

// Pour supprimer un trajet
app.post('/ride/{ride_id}/delete', (req, res) => {
})

// Pour modifier un trajet
app.post('/ride/{ride_id}/update', (req, res) => {
})

// Pour modifier ces informations utilisateur
app.post('/account/update', (req, res) => {
})

// Pour supprimer son compte
app.delete('/account/delete', (req, res) => {
})

// Pour noter un trajet
app.post('/ride/feedback', (req, res) => {
})






// POUR EMPLOYER

// Pour refuser/accepter un nouveau trajet
app.post('/ride/authorization', (req, res) => {
})


// Pour supprimer compte utilisateur/employé
app.delete('/account/delete/{id}', (req, res) => {
})

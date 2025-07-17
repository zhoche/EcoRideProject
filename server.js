const express = require('express');
const path = require('path');
const app = express();

const DIST_FOLDER = path.join(__dirname, 'dist', 'ecorideproject', 'browser');

console.log('Serving from', DIST_FOLDER);
app.use(express.static(DIST_FOLDER));

app.get('*', (req, res) => {
  res.sendFile(path.join(DIST_FOLDER, 'index.html'));
});

const PORT = parseInt(process.env.PORT, 10) || 8080;
app.listen(PORT, () => {
  console.log(`Server listening on port ${PORT}`);
});

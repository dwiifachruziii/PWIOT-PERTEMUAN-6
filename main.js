import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-app.js";
import { getDatabase, ref, onValue, query, limitToLast } from "https://www.gstatic.com/firebasejs/11.6.0/firebase-database.js";
import { firebaseConfig } from './firebase-config.js';

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);

const recordsRef = ref(database, 'records');
const latestRecordQuery = query(recordsRef, limitToLast(1));

onValue(latestRecordQuery, (snapshot) => {
  if (snapshot.exists()) {
    snapshot.forEach((childSnapshot) => {
      const data = childSnapshot.val();
      document.getElementById("temperatureValue").innerHTML = `${data.temperature} <span style="font-size: 1.5rem">°C</span>`;
      const statusClass = data.led_status === "ON" ? "status-on" : "status-off";
      document.getElementById("ledValue").innerHTML = `<span class="status-badge ${statusClass}">${data.led_status}</span>`;
    });
  } else {
    document.getElementById("temperatureValue").innerHTML = "No data";
    document.getElementById("ledValue").innerHTML = "No data";
  }
}, (error) => {
  document.getElementById("temperatureValue").innerHTML = "Error loading data";
  document.getElementById("ledValue").innerHTML = "Error loading data";
});

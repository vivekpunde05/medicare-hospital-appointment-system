/**
 * slots.js
 * Time slot selection logic for the MediCare Appointment System.
 * Loaded as a plain <script> tag — no imports/exports.
 *
 * Responsibilities:
 *  - Fetch available/booked slots from handlers/get_slots.php
 *  - Render slot buttons into #slot-container
 *  - Handle slot selection (highlight + populate hidden input)
 *  - Re-fetch slots whenever the doctor or date selection changes
 */

/**
 * Human-readable display names for each slot value.
 * @type {Object.<string, string>}
 */
var SLOT_DISPLAY_NAMES = {
  morning_9am:  '9:00 AM',
  morning_10am: '10:00 AM',
  morning_11am: '11:00 AM',
  evening_5pm:  '5:00 PM',
  evening_6pm:  '6:00 PM',
  evening_7pm:  '7:00 PM'
};

/**
 * Fetches available and booked slots for the given doctor and date from
 * `handlers/get_slots.php`, then delegates rendering to `renderSlots()`.
 * On any network or parse error, displays an inline error message in
 * #slot-container.
 *
 * @param {string} doctor - The doctor identifier (value from the doctor select).
 * @param {string} date   - The appointment date in YYYY-MM-DD format.
 * @returns {void}
 */
function fetchAvailableSlots(doctor, date) {
  var container = document.getElementById('slot-container');
  if (!container) return;

  container.innerHTML = '<p>Loading slots…</p>';

  var url = 'handlers/get_slots.php?doctor=' + encodeURIComponent(doctor) +
            '&date=' + encodeURIComponent(date);

  fetch(url)
    .then(function (response) {
      if (!response.ok) {
        throw new Error('Server returned ' + response.status);
      }
      return response.json();
    })
    .then(function (data) {
      renderSlots(data);
    })
    .catch(function () {
      if (container) {
        container.innerHTML = '<p class="slot-error">Unable to load slots, please try again.</p>';
      }
    });
}

/**
 * Renders slot buttons into #slot-container based on the data returned by
 * `handlers/get_slots.php`.
 *
 * Available slots are rendered as clickable buttons (class "slot-btn").
 * Booked slots are rendered as disabled buttons (class "slot-btn slot-booked")
 * with " (Booked)" appended to the display name.
 *
 * @param {{ available: string[], booked: string[] }} data - Slot availability data.
 * @returns {void}
 */
function renderSlots(data) {
  var container = document.getElementById('slot-container');
  if (!container) return;

  container.innerHTML = '';

  var available = Array.isArray(data.available) ? data.available : [];
  var booked    = Array.isArray(data.booked)    ? data.booked    : [];

  if (available.length === 0 && booked.length === 0) {
    container.innerHTML = '<p>No slots available for the selected date.</p>';
    return;
  }

  // Render available slots
  for (var i = 0; i < available.length; i++) {
    var slotValue = available[i];
    var displayName = SLOT_DISPLAY_NAMES[slotValue] || slotValue;

    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'slot-btn';
    btn.setAttribute('data-slot', slotValue);
    btn.textContent = displayName;

    // Closure to capture the correct slotValue for each button
    (function (value) {
      btn.addEventListener('click', function () {
        selectSlot(value);
      });
    }(slotValue));

    container.appendChild(btn);
  }

  // Render booked slots (disabled)
  for (var j = 0; j < booked.length; j++) {
    var bookedValue = booked[j];
    var bookedDisplay = SLOT_DISPLAY_NAMES[bookedValue] || bookedValue;

    var disabledBtn = document.createElement('button');
    disabledBtn.type = 'button';
    disabledBtn.className = 'slot-btn slot-booked';
    disabledBtn.setAttribute('data-slot', bookedValue);
    disabledBtn.setAttribute('disabled', 'disabled');
    disabledBtn.textContent = bookedDisplay + ' (Booked)';

    container.appendChild(disabledBtn);
  }
}

/**
 * Handles slot selection: removes the 'selected' CSS class from all slot
 * buttons, adds it to the clicked button, and populates the hidden
 * #booking-time-slot input with the chosen slot value.
 *
 * @param {string} slotValue - The slot identifier (e.g. "morning_9am").
 * @returns {void}
 */
function selectSlot(slotValue) {
  // Deselect all slot buttons
  var allBtns = document.querySelectorAll('.slot-btn');
  for (var i = 0; i < allBtns.length; i++) {
    allBtns[i].classList.remove('selected');
  }

  // Highlight the chosen button
  var chosen = document.querySelector('.slot-btn[data-slot="' + slotValue + '"]');
  if (chosen) {
    chosen.classList.add('selected');
  }

  // Populate the hidden time_slot input
  var hiddenInput = document.getElementById('booking-time-slot');
  if (hiddenInput) {
    hiddenInput.value = slotValue;
  }
}

/**
 * Triggers a slot fetch when both the doctor and date fields have values.
 * Called by the change listeners attached to #booking-doctor and #booking-date.
 *
 * @returns {void}
 */
function onDoctorOrDateChange() {
  var doctorEl = document.getElementById('booking-doctor');
  var dateEl   = document.getElementById('booking-date');

  if (!doctorEl || !dateEl) return;

  var doctor = doctorEl.value.trim();
  var date   = dateEl.value.trim();

  if (doctor && date) {
    fetchAvailableSlots(doctor, date);
  }
}

// Attach event listeners once the DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  var doctorEl = document.getElementById('booking-doctor');
  var dateEl   = document.getElementById('booking-date');

  if (doctorEl) {
    doctorEl.addEventListener('change', onDoctorOrDateChange);
  }

  if (dateEl) {
    dateEl.addEventListener('change', onDoctorOrDateChange);
  }
});

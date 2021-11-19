/*
	Some functions below are modified code from Stackoverflow post by
	Legendary_Linux. -- https://stackoverflow.com/users/2544376/legendary-linux

	For the specific post:
	See -> https://stackoverflow.com/questions/30058927/format-a-phone-number-as-a-user-types-using-pure-javascript
*/


// Verify that the event is numerical
const isNumInput = (event) => {
    const key = event.keyCode;
    return ((key >= 48 && key <= 57) || // Allow number line
        (key >= 96 && key <= 105) // Allow number pad
    );
};

// Allow modification keys
const isModKey = (event) => {
    const key = event.keyCode;
    return (event.shiftKey === true || key === 35 || key === 36) || // Allow Shift, Home, End
        (key === 8 || key === 9 || key === 13 || key === 46) || // Allow Backspace, Tab, Enter, Delete
        (key > 36 && key < 41) || // Allow left, up, right, down
        (
            // Allow Ctrl/Command + A,C,V,X,Z
            (event.ctrlKey === true || event.metaKey === true) &&
            (key === 65 || key === 67 || key === 86 || key === 88 || key === 90)
        )
};


// Detect if the code was pasted.
// if it was spread the code to the
// input boxes.
const isPasteCode = (event) => {

	// Stop regular paste behavior
	event.stopPropagation();
	event.preventDefault();

	// Capture the current ID of the event.
	const currentId = event.target.id; //Current ID value

	// Get the prefix of the ID (in case it was renamed)
  	const idTemplate = currentId.split('_')[0]; //Current ID prefix

  	// Setup the holder variables
	let clipboardData, pastedData, pastedNums, newIndex, targetElement;

	// Attempt to assign the clipboard data to the
	// variable.
	if(clipboardData = event.clipboardData || window.clipboardData)
	{
		// Pull out the actual text from the clipboard
		// event and assign it to the  variable.
		pastedData = clipboardData.getData('Text');

		// Make sure the pasted data length matches
		// the expected code length.
	  	if(parseInt(pastedData) && pastedData.length === parseInt(document.getElementById('codeLen').value))
	  	{
	  		// Get each number seperatly as an array
	  		pastedNums = pastedData.split("");

	  		// Cycle through each number and assign
	  		// it to the corresponding input field.
	  		pastedNums.forEach((element, index) => {

	  			// index starts at 0, but the count
	  			// of code form inputs start at 1,
	  			// so add one to make them match.
	  			newIndex = parseInt(index)+1;

	  			// Assign the target element to a 
	  			// variable for easier referencing.
	  			targetElement = document.getElementById(idTemplate + '_' + newIndex);

	  			// Enable the input field if it
	  			// is currently disabled
	  			if(targetElement.disabled)
				{
					targetElement.disabled = false;
				}

				// Assign all of the default listeners to the newly
				// enabled field.
				targetElement.addEventListener('keydown',enforceFormat);
				targetElement.addEventListener('keydown',isUndo);
				targetElement.addEventListener('keydown',limitChars);
				targetElement.addEventListener('keyup',movetoNext);

				// Assign the code to the input. Repeat.
				targetElement.value = element;
			});

			// Set the focus to the last pasted
			// input.
			targetElement.focus();
	  	}
	}
	
};


// Use javascript to reset the code form
// to ensure the fields are put back
// properly (disabled, blank, etc.)
function resetForm(event)
{
  const idPrefix = 'fieldCode'; //Defined ID prefix
  const codeLen = document.getElementById('codeLen').value; //Amount of fields to expect

  let currentField;

  // Cycle through the input fields
  // and disabled them all except the 
  // first one. Then set if as focused.
  for(let i = codeLen; i >= 1; i--)
  {
  	currentField = document.getElementById(idPrefix + '_' + i);
  	currentField.value = '';
  	if(i > 1)
  	{
  		currentField.disabled = true;
  	}
  	else
  	{
  		currentField.focus();
  	}
  }
  
}

// Caputure ctrl/cmd+Z
// and reset the form.
const isUndo = (event) => {
	if(isModKey(event))
	{
		const key = event.keyCode;
		if(key === 90)
		{
			resetForm(event);
		}
	}
};

//
const enforceFormat = (event) => {

	// Stop the typing if the event
	// isn't numeric or an allowed
	// modification key.
  	if(!isNumInput(event) && !isModKey(event)){
 			event.preventDefault();
 	}
 	else {

 		// Handle backspace keystroke so that
 		// it will take you back to the 
 		// previous code input and disable the
 		// current one again.
		if(isModKey(event))
		{
			const key = event.keyCode; // current keystroke
			const currentId = event.target.id; //Current ID value
		  	const idtemplate = currentId.split('_')[0]; // Split ID number, get the prefix
		  	const idNumber = currentId.split('_')[1]; // Split ID number, get the suffix
		  	const prevIdNum = parseInt(idNumber) - 1; // previous ID Number
		  	const prevId = idtemplate + "_" + prevIdNum; // Previous ID
			const prevEl = document.getElementById(prevId); // Assign previous element to var
			const currEl = document.getElementById(currentId); // Assign current element to var
			// Check that the key is correct and
			// we aren't at the first input
			if(key === 8 && prevIdNum > 0) {

				// Double check we arent at the first
				// input element.
				if(prevEl != null) {

					// Enable the previous input
					prevEl.disabled = false;

					// Erase the current input
					currEl.value = "";

					// Disable the current input
					currEl.disabled = true;

					// Set the focus to the previous input
					prevEl.focus();
				}
			}
		}
	}
};


// For onload event. This sets the 
// opening focus to the first code
// input.
const codeFocus = (event) => {
	document.querySelector('[type="number"]').focus();
};

// Limit the characters to 1
// on the current input
const limitChars = (event) => {


	const currentValue = event.target.value; // Current value.
	const maxLen = event.target.maxLength; // Current field maxLength
	const currentLen = currentValue.length; // Current length.

	// Check to see if the input is to long
	if(currentLen >= maxLen)
	{
		// Stop the typing and return the value
		// to 1 digit.
		event.preventDefault();
		event.target.value = currentValue.substring(0, 1);
	}
};

// Fuction to move to the next disabled
// input when the current one is filled.
const movetoNext = (event) => {
	const currentId = event.target.id; //Current ID value
  	const idtemplate = currentId.split('_')[0]; // Split ID number, get the prefix
  	const idNumber = currentId.split('_')[1]; // Split ID number, get the suffix
  	const nextIdNum = parseInt(idNumber) + 1; // Next ID Number
  	const nextId = idtemplate + "_" + nextIdNum; // Next ID
		const currentValue = event.target.value; // Current value.
		const maxLen = event.target.maxLength; // Current field maxLength
		const currentLen= currentValue.length; // Current length.
		const formEl = event.target.parentNode; // Get the form calling event. This is not required yet.
		
		// Check to see if the input is to long
		if(currentLen >= maxLen)
		{
			// Stop the typing and return the value
			// to 1 digit.
			event.preventDefault();
			event.target.value = currentValue.substring(0, 1);

			// Verify the key pressed isn't an allowed
			// modification key.
			if(!isModKey(event))
			{
				// Get the next element by ID.
				const nextEl = document.getElementById(nextId);

				// Make sure the element exists.
				if(nextEl != null)
				{
					// Enabled the field and assign
					// all of the default listeners.
					nextEl.disabled = false;
					nextEl.focus();
					nextEl.addEventListener('keydown',enforceFormat);
					nextEl.addEventListener('keydown',isUndo);
					nextEl.addEventListener('keydown',limitChars);
					nextEl.addEventListener('keyup',movetoNext);
				}
				else
				{
					// We are at the last element. Shift
					// focus to the submit button.
					let submitButton = document.querySelector('[type="submit"]');
					submitButton.addEventListener('keydown',isUndo);
					submitButton.focus();
				}
			}
		}
};


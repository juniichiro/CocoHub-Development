const toTitleCase = (str) => {
    if (!str) return "";
    return str.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());
};

const updateFullName = () => {
    const first = toTitleCase(document.getElementById('first_name')?.value.trim() || "");
    const middle = toTitleCase(document.getElementById('middle_name')?.value.trim() || "");
    const last = toTitleCase(document.getElementById('last_name')?.value.trim() || "");
    
    let fullName = first;
    if (middle) fullName += ' ' + middle;
    if (last) fullName += ' ' + last;
    
    const hiddenInput = document.getElementById('hidden_name');
    if (hiddenInput) hiddenInput.value = fullName;
};

window.formatInput = (element) => {
    element.value = toTitleCase(element.value);
    updateFullName();
};

window.updateFullName = updateFullName;
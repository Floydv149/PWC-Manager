export function getAvailableIndex(object) {
	let lowestIndex = 0;

	if (Object.keys(object).length === 0) {
		return 0;
	}

	for (const index in object) {
		if (parseInt(index) == lowestIndex + 1) {
			lowestIndex = parseInt(index);
		}
	}

	return lowestIndex + 1;
}

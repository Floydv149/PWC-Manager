export function getAvailableIndex(object) {
	let lowestIndex = 0;

	for (const index in object) {
		if (index == lowestIndex + 1) {
			lowestIndex = index;
		}
	}

	return lowestIndex + 1;
}

const fs = require('fs');
const crypto = require('crypto');
const cryptoHash = crypto.createHash('sha256');

const returnHash = (filename) => {
	const input = fs.createReadStream(filename);
	return new Promise((resolve, reject) => {
		input.on('readable', () => {
			const data = input.read();
			if (data) {
				cryptoHash.update(data);
			} else {
				resolve(cryptoHash.digest('hex').substring(0,10));
			}
		});
		input.on('error', (error) => reject(error));
	});
};

module.exports = returnHash;

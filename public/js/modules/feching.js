export class Fetching {
    constructor(url) {
        this.url = url;
        this.go = async function () {

            try {
                const response = await fetch(this.url);
                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }

                const data = await response.json();
                console.log(data);

            } catch (error) {
                console.error(error.message);
            }

        }
    }
};
document.addEventListener('DOMContentLoaded', function() {
    const collectionHolder = document.querySelector('#invoiceDetails') ?? document.querySelector('#quotationDetails');
    collectionHolder.dataset.index = collectionHolder.querySelectorAll('.invoiceDetailItem, .quotationDetailItem').length;

    const addItemButton = document.querySelector('.add-detail');

    addItemButton.addEventListener('click', function(e) {
        const newFormDiv = document.createElement('div');
        newFormDiv.className = 'flex items-center justify-between bg-gray-100 p-4 rounded mb-4';
        
        const index = collectionHolder.dataset.index;
        const newForm = collectionHolder.dataset.prototype.replace(/__name__/g, index);

        collectionHolder.dataset.index++;

        newFormDiv.innerHTML = newForm;
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'remove-detail bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded';
        removeButton.innerText = 'x';

        removeButton.addEventListener('click', function(e) {
            newFormDiv.remove();
            updateTotal();
        });

        newFormDiv.appendChild(removeButton);
        collectionHolder.appendChild(newFormDiv);
 
        // attacher les événements aux selects
        attachSelectChangeEvent(newFormDiv);
        // mettre à jour le total
        updateTotal();
    });

    const detailItem = collectionHolder.querySelectorAll('.invoiceDetailItem, .quotationDetailItem');
    detailItem.forEach(item => {
        const removeButton = item.querySelector('.remove-detail');
        if (removeButton) {
            removeButton.addEventListener('click', function(e) {
                item.remove();
                updateTotal();
            });
        }
        attachSelectChangeEvent(item);
    });

    function attachSelectChangeEvent(item) {
        const selects = item.querySelectorAll('select');
        selects.forEach(select => {
            select.addEventListener('change', updateTotal);
        });
    }

    document.addEventListener('change', updateTotal);

    function updateTotal() {
        let totalHT = 0;
        let totalTTC = 0;
    
        const selects = document.querySelectorAll('select');
        const inputs = document.querySelectorAll('[id$="_quantity"]');
        const tvas = document.querySelectorAll('[id$="_tva"]');
    
        for (let i = 0; i < selects.length; i++) {
            const select = selects[i];
            const input = inputs[i];
            const tva = tvas[i];
        
            if (select && input && tva) {
                const selectedOption = select.options[select.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price'));
                const quantity = parseFloat(input.value);
                const tvaValue = parseFloat(tva.value);
        
                if (!isNaN(price) && !isNaN(quantity) && !isNaN(tvaValue)) {
                    const lineTotalHT = price * quantity;
                    const lineTotalTTC = lineTotalHT * (1 + tvaValue / 100);
        
                    totalHT += lineTotalHT;
                    totalTTC += lineTotalTTC;
                }
            }
        }
    
        document.querySelector('.totalTTC').innerText = isNaN(totalTTC) ? '0.00 €' : totalTTC.toFixed(2) + ' €';
        document.querySelector('.totalHT').innerText = isNaN(totalHT) ? '0.00 €' : totalHT.toFixed(2) + ' €';
        document.querySelector('[id$="_totalHT"]').value = isNaN(totalTTC) ? '0.00' : totalTTC.toFixed(2);
        document.querySelector('[id$="_totalTTC"]').value = isNaN(totalHT) ? '0.00' : totalHT.toFixed(2);
    }
});

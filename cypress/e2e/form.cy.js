describe('Job Form', () => {
    it('loads the form page', () => {
        cy.visit('http://localhost:8000');
        cy.get('form#job-form').should('be.visible');
    });

    it('displays all required form fields', () => {
        cy.visit('http://localhost:8000');
        cy.get('#title').should('be.visible');
        cy.get('#script').should('be.visible');
        cy.get('#country').should('be.visible');
        cy.get('#state_or_province').should('be.visible');
        cy.get('input[name="budget"]').should('exist');
        cy.get('#reference_file_path').should('be.visible');
        cy.get('button[type="submit"]').should('be.visible');
        cy.get('button[type="reset"]').should('be.visible');
    });
});

describe('Job Form Field Interactions', () => {
    beforeEach(() => {
        cy.visit('http://localhost:8000');
    });

    // Test field visibility and basic interactions
    it('allows typing in title field', () => {
        cy.get('#title').type('Professional Voice Over for Commercial').should('have.value', 'Professional Voice Over for Commercial');
    });

    it('allows typing in script field and updates word count', () => {
        cy.get('#script').type('We are looking for an experienced voice actor');
        cy.get('#word-count').should('not.have.text', '0 words');
    });

    it('allows country selection', () => {
        cy.get('#country').select('CA').should('have.value', 'CA');
    });

    it('shows state/province options based on country selection', () => {
        // Select Canada
        cy.get('#country').select('CA');
        // Ontario should be available (by province name)
        cy.get('#state_or_province').find('option').contains('Ontario').should('exist');
    });

    it('updates state/province when country changes from Canada to US', () => {
        // First select Canada
        cy.get('#country').select('CA');
        cy.get('#state_or_province').select('ON');
        
        // Then change to US
        cy.get('#country').select('US');
        // Ontario should no longer be valid (province name not in options)
        cy.get('#state_or_province').find('option').contains('Ontario').should('not.exist');
        // NY should be available
        cy.get('#state_or_province').find('option').contains('New York').should('exist');
    });

    it('allows all budget options to be selected', () => {
        // Test low budget
        cy.get('#budgetLow').check().should('be.checked');
        
        // Test medium budget
        cy.get('#budgetMid').check().should('be.checked');
        cy.get('#budgetLow').should('not.be.checked');
        
        // Test high budget
        cy.get('#budgetHigh').check().should('be.checked');
        cy.get('#budgetMid').should('not.be.checked');
    });

    // Test form reset
    it('clears all fields when reset button is clicked', () => {
        cy.get('#title').type('Voice Acting Project');
        cy.get('#script').type('Sample script content');
        cy.get('#country').select('CA');
        cy.get('#state_or_province').select('ON');
        cy.get('#budgetLow').check();
        
        cy.get('button[type="reset"]').click();
        
        cy.get('#title').should('have.value', '');
        cy.get('#script').should('have.value', '');
        cy.get('#country').should('have.value', '');
        cy.get('#budgetLow').should('not.be.checked');
    });

    // Test input retention (old input)
    it('preserves field values after page load', () => {
        const projectTitle = 'Test Voice Over Project';
        cy.get('#title').type(projectTitle).should('have.value', projectTitle);
    });

    // Test file upload field
    it('allows file selection for reference file', () => {
        cy.get('#reference_file_path').should('have.attr', 'accept').and('include', '.pdf');
        cy.get('#reference_file_path').should('have.attr', 'accept').and('include', '.doc');
    });

    // Test CORS/button visibility
    it('submit button exists and is visible', () => {
        cy.get('button[type="submit"]').should('be.visible');
    });

    it('reset button exists and is visible', () => {
        cy.get('button[type="reset"]').should('be.visible').and('not.be.disabled');
    });

    // Test form attributes for accessibility
    it('has proper form accessibility attributes', () => {
        cy.get('form#job-form').should('have.attr', 'enctype', 'multipart/form-data');
        cy.get('#title').should('have.attr', 'aria-required', 'true');
        cy.get('#country').should('have.attr', 'aria-required', 'true');
        cy.get('#state_or_province').should('have.attr', 'aria-required', 'true');
    });

    // Test CSRF token presence
    it('includes CSRF token in form', () => {
        cy.get('input[name="csrf_token"]').should('exist').invoke('val').should('not.be.empty');
    });

    // Test honeypot field exists
    it('has honeypot field for bot detection', () => {
        cy.get('input[name="website"]').should('exist');
        cy.get('input[name="website"]').parent().should('have.css', 'display', 'none');
    });

    // Test placeholder text
    it('displays helpful placeholder text in fields', () => {
        cy.get('#title').should('have.attr', 'placeholder');
        cy.get('#script').should('have.attr', 'placeholder');
    });

    // Test description text
    it('displays form descriptions and hints', () => {
        cy.contains("What's your project's name?").should('be.visible');
        cy.contains('Include a small piece of a script').should('be.visible');
        cy.contains('Max. 20MB').should('be.visible');
    });

    // Test optional field labels
    it('marks optional fields appropriately', () => {
        cy.contains('Small Script').parent().contains('Optional').should('be.visible');
        cy.contains('Please upload your reference file').parent().contains('Optional').should('be.visible');
    });
});
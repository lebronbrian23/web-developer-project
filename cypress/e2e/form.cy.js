describe('Job Form', () => {
    it('loads the form page', () => {
        cy.visit('http://localhost:8000'); 
        //cy.get('form').should('be.visible'); 
    });
});
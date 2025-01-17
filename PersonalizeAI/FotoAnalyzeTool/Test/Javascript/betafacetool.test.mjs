// Test/Javascript/betafacetool.test.js

define(['jquery', 'sinon', 'mage/url', '../../view/frontend/web/js/betafacetool.js'], function ($, sinon, urlBuilder, betafacetool) {
    'use strict';

    describe('BetaFaceTool', function() {
        let module;
        let fetchStub;

        beforeEach(function() {
            // Initialize the module
            module = betafacetool({
                facebookProfilePicUrl: 'https://example.com/profile_picture.jpg'
            });

            // Stub the fetch API
            fetchStub = sinon.stub(window, 'fetch');
            
            // Mocking DOM elements
            document.body.innerHTML = `
                <div id="result"></div>
                <input type="file" id="file" />
                <button class="analyze-button">Analyze</button>
            `;
        });

        afterEach(function() {
            // Restore the original fetch function
            fetchStub.restore();
        });

        it('should successfully analyze a Facebook profile picture', async function() {
            const mockResponse = {
                media: {
                    faces: [
                        {
                            tags: [
                                { name: 'happy', value: true, confidence: 0.95 },
                                { name: 'smiling', value: true, confidence: 0.90 }
                            ]
                        }
                    ]
                }
            };

            // Set up the fetch stub to return a successful response
            fetchStub.resolves(new Response(JSON.stringify(mockResponse), { status: 200 }));

            await module.analyzeFacebookProfilePic();

            expect(document.getElementById('result').innerHTML).to.contain('happy'); // Check if the result contains expected data
        });

        it('should throw an error if the API call fails', async function() {
            // Set up the fetch stub to return an error response
            fetchStub.rejects(new Error('Network Error'));

            try {
                await module.analyzeFacebookProfilePic();
                expect.fail('Expected error to be thrown');
            } catch (error) {
                expect(error.message).to.equal('Network Error'); // Check if the error message matches
                expect(document.getElementById('result').innerHTML).to.contain('An error occurred while analyzing the image.'); // Check UI update
            }
        });
    });
});
